<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\PenilaianController;
use App\Http\Services\ApiSettingService;
use App\Http\Services\PatientService;
use App\Http\Services\PointService;
use App\Http\Services\WhatsappService;
use App\Mail\SendKurvaMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Helper\CacheHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class PdfController extends Controller
{
    protected $pointService;
    protected $patientService;
    protected $apiSettingService;
    protected $whatsappService;
    protected $emailDefault;
    protected $senderNameDefault;
    protected $superAdmin;
    protected $pdfSetting;
    private $headerKey = 'header';
    private $logo;

    public function __construct(PointService $pointService, PatientService $patientService, ApiSettingService $apiSettingService, WhatsappService $whatsappService)
    {
        $superAdmin = CacheHelper::getSuperAdmin();

        $this->patientService = $patientService;
        $this->pointService = $pointService;
        $this->apiSettingService = $apiSettingService;
        $this->whatsappService = $whatsappService;

        $this->emailDefault = $superAdmin->email;
        $senderName = Cache::rememberForever('pdf_settings_sender_name', function () {
            return DB::table('pdf_settings')->where('key', 'sender_name')->first();
        });
        $this->superAdmin = $superAdmin;
        $this->senderNameDefault = $senderName ? $senderName->value : 'cs.ptekai@gmail.com';
        $this->setLogo();

        $this->pdfSetting = Cache::rememberForever('pdf_settings', function () {
            $settings = DB::table('pdf_settings')->get();
            return $settings->isEmpty() ? collect([]) : $settings;
        });
    }
    /**
     * Set logo berdasarkan user yang login
     * Jika tidak ada, gunakan logo dari super admin
     */
    private function setLogo()
    {
        // Cek header user yang login dulu
        $headerUser = null;
        if (Auth::user()->roles()->first()->name != 'super-admin' && !Auth::user()->isSupportheader()) {
            if (Auth::user()->is_nakes) {
                $headerUser = Auth::user()->instansi->header ?? null;
            } else {
                $headerUser = Auth::user()->header ?? null;
            }
        }

        // Jika tidak ada, gunakan header dari super admin
        if (!$headerUser) {
            $headerSetting = Cache::rememberForever('pdf_header', function () {
                return DB::table('lp_settings')->where('key', $this->headerKey)->first();
            });

            $headerUser = $headerSetting ? $headerSetting->value : null;
        }

        if ($headerUser && file_exists(public_path("img-public/header/{$headerUser}"))) {
            $this->logo = $headerUser;
        } else {
            $this->logo = null;
        }
    }

    public function saveChart(Request $request)
    {
        try {
            $data = $request->input('image');
            $filename = $request->input('filename');

            if (!$data || !$filename) {
                Log::error("Gagal menerima data gambar atau filename.");
                return response()->json(['status' => 'failed', 'message' => 'Missing data'], 400);
            }

            // Ekstrak base64
            list($type, $data) = explode(';', $data);
            list(, $data) = explode(',', $data);
            $data = base64_decode($data);

            $path = public_path('img-public/kurva');
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }

            file_put_contents($path . '/' . $filename, $data);

            Log::info("Gambar {$filename} berhasil disimpan di {$path}");

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error("Error saat menyimpan chart: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan'], 500);
        }
    }

    // Debug PDF
    public function debugPdf(Request $request, $patientId)
    {
        try {
            // Extract selected points from request
            $selectedPoints = $request->input('selectedPoints') ?? [];

            // Log the received data for debugging
            Log::info("Received selectedPoints data:", ['data' => $selectedPoints]);

            if (empty($selectedPoints)) {
                Log::error("Tidak ada data yang dipilih untuk download PDF.");
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak ada data point yang dipilih'
                ], 400);
            }

            // Get point setting for download PDF
            $context = getInstansiOrUserContext(Auth::user());
            $pointSetting = $this->pointService->findSettingByName('DOWNLOAD-GRAFIK');
            $pointSettingHeader = $this->pointService->findSettingByName('TAMBAH-HEADER');

            if (Auth::user()->isSupportHeader()) {
                $hasHeader = false;
            } else {
                $hasHeader = Auth::user()->is_nakes ? Auth::user()->instansi->header : Auth::user()->header;
            }

            if ($hasHeader) {
                // Check jika user mempunyai point cukup
                $isEnough = $this->pointService->isPointEnough(
                    $context['user_id'],
                    $context['instansi_id'],
                    ($pointSetting->points + $pointSettingHeader->points)
                );
            } else {
                $isEnough = $this->pointService->isPointEnough(
                    $context['user_id'],
                    $context['instansi_id'],
                    $pointSetting->points
                );
            }

            if (!$isEnough) {
                Log::error("Poin tidak cukup untuk download PDF. User ID: {$context['user_id']}");
                return response()->json([
                    'status' => 'error',
                    'message' => 'Poin Anda tidak cukup atau masa aktif poin Anda sudah habis untuk download PDF! Silahkan top up poin terlebih dahulu.'
                ], 403);
            }

            $penilaianController = new PenilaianController($this->patientService, $this->pointService);
            $result = $penilaianController->prosesPenilaian($selectedPoints, $patientId);

            if (!$result) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak dapat memproses data penilaian'
                ], 400);
            }

            $patient = $result['patient'];
            $latestAntro = $result['latestAntro'];
            $interpretasiGizi = $result['interpretasiGizi'];

            $images = [];
            $kurvaTableSettings = DB::table('kurva_table_settings')->select('judul', 'nama_tabel')->get()->keyBy('nama_tabel')->toArray();

            for ($i = 1; $i <= 12; $i++) {
                $path = public_path("img-public/kurva/chart-{$patientId}-table{$i}.png");

                if (!file_exists($path)) {
                    Log::warning("Gambar chart-{$patientId}-table{$i}.png tidak ditemukan di kurva/");
                    continue;
                }

                $images[] = ("img-public/kurva/chart-{$patientId}-table{$i}.png");
            }

            // if (count($images) === 0) {
            //     Log::error("Tidak ada gambar yang ditemukan untuk PDF.");
            //     return response()->json(['status' => 'error', 'message' => 'Tidak ada gambar'], 404);
            // }

            // Get ads image if available
            $adsImage = null;
            $adsSetting = Cache::rememberForever('pdf_ads', function () {
                return DB::table('lp_settings')->where('key', 'ads')->first();
            });
            if ($adsSetting && !empty($adsSetting->value)) {
                $adsPath = public_path($adsSetting->value);
                if (file_exists($adsPath)) {
                    $adsImage = $adsSetting->value;
                    Log::info("Ads image found and will be included in PDF: {$adsImage}");
                    // Add ads image to the images array to be processed in the loop
                    $images[] = $adsSetting->value;
                }
            }

            // Extract just the IDs from the selectedPoints data structure
            $pointIds = [];
            if (is_array($selectedPoints)) {
                // Check if we have a nested data structure
                if (isset($selectedPoints['data']) && is_array($selectedPoints['data'])) {
                    foreach ($selectedPoints['data'] as $point) {
                        if (isset($point['id'])) {
                            $pointIds[] = $point['id'];
                        }
                    }
                } else {
                    // Try to extract IDs if they're directly in the array
                    foreach ($selectedPoints as $point) {
                        if (is_array($point) && isset($point['id'])) {
                            $pointIds[] = $point['id'];
                        } elseif (is_numeric($point)) {
                            $pointIds[] = $point;
                        }
                    }
                }
            } else if (is_numeric($selectedPoints)) {
                $pointIds = [$selectedPoints];
            }

            // Now use the extracted IDs with whereIn
            $antros = DB::table('antro_patients')
                ->whereIn('antro_patients.id', $pointIds)
                ->where(function ($query) {
                    $query->whereNotNull('antro_patients.notes')
                        ->where('antro_patients.notes', '!=', '');
                })
                ->leftJoin('users', 'antro_patients.created_by', '=', 'users.id')
                ->select('antro_patients.tgl_periksa', 'antro_patients.notes', 'users.name as created_by')
                ->orderBy('antro_patients.tgl_periksa', 'desc')
                ->get();

            $pdf = PDF::loadView('pdf.kurva', [
                'images' => $images,
                'patient' => $patient,
                'kurvaTableSettings' => $kurvaTableSettings,
                'logo' => $this->logo,
                'antros' => $antros,
                'latestAntro' => $latestAntro,
                'interpretasiGizi' => $interpretasiGizi,
                'superAdmin' => $this->superAdmin,
                'pdfSetting' => $this->pdfSetting,
            ])
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'defaultFont' => 'DejaVu Sans',
                    'enable_remote' => false,
                    'chroot' => public_path('img-public'),
                    'defaultPaperSize' => 'a4',
                    'margin_top' => 8,      // dalam satuan milimeter
                    'margin_right' => 8,
                    'margin_bottom' => 8,
                    'margin_left' => 8,
                ]);

            // Format file name
            $filename = $this->generateFileName($patient, $latestAntro);

            Storage::disk('public')->put("kurva/{$filename}", $pdf->output());

            Log::info("PDF berhasil dibuat dan disimpan di storage/kurva/{$filename}");

            // Kurangi poin setelah PDF berhasil dibuat
            if ($hasHeader) {
                $this->pointService->usage(
                    $context['user_id'],
                    $context['instansi_id'],
                    ($pointSetting->points + $pointSettingHeader->points),
                    'Download Grafik PDF + Header',
                    $pointSetting->id,
                    $patientId
                );
            } else {
                $this->pointService->usage(
                    $context['user_id'],
                    $context['instansi_id'],
                    $pointSetting->points,
                    'Download Grafik PDF',
                    $pointSetting->id,
                    $patientId
                );
            }

            // Hapus gambar setelah PDF dibuat
            foreach ($images as $imgPath) {
                // Skip deletion if this is the ads image
                if (strpos($imgPath, 'ads_') !== false || strpos($imgPath, 'lp-setting/ads_') !== false) {
                    Log::info("Skipping deletion of ads image: {$imgPath}");
                    continue;
                }

                $fullPath = public_path($imgPath);
                if (File::exists($fullPath)) {
                    File::delete($fullPath);
                    Log::info("Gambar {$imgPath} berhasil dihapus.");
                }
            }

            // Hapus file PDF setelah dikirim
            $pathToDelete = storage_path("app/public/kurva/{$filename}");

            if (File::exists($pathToDelete)) {
                File::delete($pathToDelete);
                Log::info("File PDF {$filename} berhasil dihapus setelah dikirim.");
            }

            if ($request->has('skip_confirmation') && $request->skip_confirmation) {
                Cookie::queue('skip_confirm', 'true', 60 * 24 * 30); // 30 hari
            }

            // Stream the PDF directly to the user
            return response()->stream(
                function () use ($pdf) {
                    echo $pdf->output();
                },
                200,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                ]
            );

            // return response($pdf->output(), 200)
            //     ->header('Content-Type', 'application/pdf')
            //     ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } catch (\Exception $e) {
            Log::error("Error saat generate PDF: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Gagal membuat PDF'], 500);
        }
    }

    public function generatePdf(Request $request, $patientId)
    {
        try {
            // Extract selected points from request
            $selectedPoints = $request->input('selectedPoints') ?? [];

            // Log the received data for debugging
            Log::info("Received selectedPoints data:", ['data' => $selectedPoints]);

            if (empty($selectedPoints)) {
                Log::error("Tidak ada data yang dipilih untuk download PDF.");
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak ada data point yang dipilih'
                ], 400);
            }

            // Get point setting for download PDF
            $context = getInstansiOrUserContext(Auth::user());
            $pointSetting = $this->pointService->findSettingByName('DOWNLOAD-GRAFIK');
            $pointSettingHeader = $this->pointService->findSettingByName('TAMBAH-HEADER');

            if (Auth::user()->isSupportHeader()) {
                $hasHeader = false;
            } else {
                $hasHeader = Auth::user()->is_nakes ? Auth::user()->instansi->header : Auth::user()->header;
            }

            if ($hasHeader) {
                // Check jika user mempunyai point cukup
                $isEnough = $this->pointService->isPointEnough(
                    $context['user_id'],
                    $context['instansi_id'],
                    ($pointSetting->points + $pointSettingHeader->points)
                );
            } else {
                $isEnough = $this->pointService->isPointEnough(
                    $context['user_id'],
                    $context['instansi_id'],
                    $pointSetting->points
                );
            }

            if (!$isEnough) {
                Log::error("Poin tidak cukup untuk download PDF. User ID: {$context['user_id']}");
                return response()->json([
                    'status' => 'error',
                    'message' => 'Poin Anda tidak cukup atau masa aktif poin Anda sudah habis untuk download PDF! Silahkan top up poin terlebih dahulu.'
                ], 403);
            }

            $penilaianController = new PenilaianController($this->patientService, $this->pointService);
            $result = $penilaianController->prosesPenilaian($selectedPoints, $patientId);

            if (!$result) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak dapat memproses data penilaian'
                ], 400);
            }

            $patient = $result['patient'];
            $latestAntro = $result['latestAntro'];
            $interpretasiGizi = $result['interpretasiGizi'];

            $images = [];
            $kurvaTableSettings = DB::table('kurva_table_settings')->select('judul', 'nama_tabel')->get()->keyBy('nama_tabel')->toArray();

            for ($i = 1; $i <= 12; $i++) {
                $path = public_path("img-public/kurva/chart-{$patientId}-table{$i}.png");

                if (!file_exists($path)) {
                    Log::warning("Gambar chart-{$patientId}-table{$i}.png tidak ditemukan di kurva/");
                    continue;
                }

                $images[] = ("img-public/kurva/chart-{$patientId}-table{$i}.png");
            }

            if (count($images) === 0) {
                Log::error("Tidak ada gambar yang ditemukan untuk PDF.");
                return response()->json(['status' => 'error', 'message' => 'Tidak ada gambar'], 404);
            }

            // Get ads image if available
            $adsImage = null;
            $adsSetting = Cache::rememberForever('pdf_ads', function () {
                return DB::table('lp_settings')->where('key', 'ads')->first();
            });
            if ($adsSetting && !empty($adsSetting->value)) {
                $adsPath = public_path($adsSetting->value);
                if (file_exists($adsPath)) {
                    $adsImage = $adsSetting->value;
                    Log::info("Ads image found and will be included in PDF: {$adsImage}");
                    // Add ads image to the images array to be processed in the loop
                    $images[] = $adsSetting->value;
                }
            }

            // Extract just the IDs from the selectedPoints data structure
            $pointIds = [];
            if (is_array($selectedPoints)) {
                // Check if we have a nested data structure
                if (isset($selectedPoints['data']) && is_array($selectedPoints['data'])) {
                    foreach ($selectedPoints['data'] as $point) {
                        if (isset($point['id'])) {
                            $pointIds[] = $point['id'];
                        }
                    }
                } else {
                    // Try to extract IDs if they're directly in the array
                    foreach ($selectedPoints as $point) {
                        if (is_array($point) && isset($point['id'])) {
                            $pointIds[] = $point['id'];
                        } elseif (is_numeric($point)) {
                            $pointIds[] = $point;
                        }
                    }
                }
            } else if (is_numeric($selectedPoints)) {
                $pointIds = [$selectedPoints];
            }

            $antros = DB::table('antro_patients')
                ->whereIn('antro_patients.id', $pointIds)
                ->where(function ($query) {
                    $query->whereNotNull('antro_patients.notes')
                        ->where('antro_patients.notes', '!=', '');
                })
                ->leftJoin('users', 'antro_patients.created_by', '=', 'users.id')
                ->select('antro_patients.tgl_periksa', 'antro_patients.notes', 'users.name as created_by')
                ->orderBy('antro_patients.tgl_periksa', 'desc')
                ->get();

            $pdf = PDF::loadView('pdf.kurva', [
                'images' => $images,
                'patient' => $patient,
                'kurvaTableSettings' => $kurvaTableSettings,
                'logo' => $this->logo,
                'antros' => $antros,
                'latestAntro' => $latestAntro,
                'interpretasiGizi' => $interpretasiGizi,
                'superAdmin' => $this->superAdmin,
                'pdfSetting' => $this->pdfSetting,
            ])
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'enable_remote' => false,
                    'chroot' => public_path('img-public'),
                    'defaultPaperSize' => 'a4',
                    'margin_top' => 8,      // dalam satuan milimeter
                    'margin_right' => 8,
                    'margin_bottom' => 8,
                    'margin_left' => 8,
                ]);

            // Format file name
            $filename = $this->generateFileName($patient, $latestAntro);

            Storage::disk('public')->put("kurva/{$filename}", $pdf->output());

            Log::info("PDF berhasil dibuat dan disimpan di storage/kurva/{$filename}");

            // Kurangi poin setelah PDF berhasil dibuat
            if ($hasHeader) {
                $this->pointService->usage(
                    $context['user_id'],
                    $context['instansi_id'],
                    ($pointSetting->points + $pointSettingHeader->points),
                    'Download Grafik PDF + Header',
                    $pointSetting->id,
                    $patientId
                );
            } else {
                $this->pointService->usage(
                    $context['user_id'],
                    $context['instansi_id'],
                    $pointSetting->points,
                    'Download Grafik PDF',
                    $pointSetting->id,
                    $patientId
                );
            }

            // Hapus gambar setelah PDF dibuat
            foreach ($images as $imgPath) {
                // Skip deletion if this is the ads image
                if (strpos($imgPath, 'ads_') !== false || strpos($imgPath, 'lp-setting/ads_') !== false) {
                    Log::info("Skipping deletion of ads image: {$imgPath}");
                    continue;
                }

                $fullPath = public_path($imgPath);
                if (File::exists($fullPath)) {
                    File::delete($fullPath);
                    Log::info("Gambar {$imgPath} berhasil dihapus.");
                }
            }

            // Hapus file PDF setelah dikirim
            $pathToDelete = storage_path("app/public/kurva/{$filename}");

            if (File::exists($pathToDelete)) {
                File::delete($pathToDelete);
                Log::info("File PDF {$filename} berhasil dihapus setelah dikirim.");
            }

            if ($request->has('skip_confirmation') && $request->skip_confirmation) {
                Cookie::queue('skip_confirm', 'true', 60 * 24 * 30); // 30 hari
            }

            return response($pdf->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } catch (\Exception $e) {
            Log::error("Error saat generate PDF: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Gagal membuat PDF'], 500);
        }
    }

    public function generateAndSendPdf(Request $request, $patientId)
    {
        try {
            // Extract selected points from request
            $selectedPoints = $request->input('selectedPoints') ?? [];
            $senderName = $request->input('displayName') ?? null;

            // Log the received data for debugging
            Log::info("Received selectedPoints data:", ['data' => $selectedPoints]);

            if (empty($selectedPoints)) {
                Log::error("Tidak ada data yang dipilih untuk download PDF.");
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak ada data point yang dipilih'
                ], 400);
            }

            // Get point setting for download PDF
            $context = getInstansiOrUserContext(Auth::user());
            $pointSetting = $this->pointService->findSettingByName('PUSH-EMAIL-GRAFIK');
            $pointSettingHeader = $this->pointService->findSettingByName('TAMBAH-HEADER');
            $pointSettingSenderName = $this->pointService->findSettingByName('EMAIL-CUSTOM');

            if (Auth::user()->isSupportHeader()) {
                $hasHeader = false;
            } else {
                $hasHeader = Auth::user()->is_nakes ? Auth::user()->instansi->header : Auth::user()->header;
            }
            $hasSenderName = $senderName ? true : false;

            if ($hasHeader && $hasSenderName) {
                // Check jika user mempunyai point cukup
                $isEnough = $this->pointService->isPointEnough(
                    $context['user_id'],
                    $context['instansi_id'],
                    ($pointSetting->points + $pointSettingHeader->points + $pointSettingSenderName->points)
                );
            } elseif ($hasHeader) {
                // Check jika user mempunyai point cukup
                $isEnough = $this->pointService->isPointEnough(
                    $context['user_id'],
                    $context['instansi_id'],
                    ($pointSetting->points + $pointSettingHeader->points)
                );
            } elseif ($hasSenderName) {
                // Check jika user mempunyai point cukup
                $isEnough = $this->pointService->isPointEnough(
                    $context['user_id'],
                    $context['instansi_id'],
                    ($pointSetting->points + $pointSettingSenderName->points)
                );
            } else {
                // Check jika user mempunyai point cukup
                $isEnough = $this->pointService->isPointEnough(
                    $context['user_id'],
                    $context['instansi_id'],
                    $pointSetting->points
                );
            }

            if (!$isEnough) {
                Log::error("Poin tidak cukup untuk download PDF. User ID: {$context['user_id']}");
                return response()->json([
                    'status' => 'error',
                    'message' => 'Poin Anda tidak cukup atau masa aktif poin Anda sudah habis untuk download PDF! Silahkan top up poin terlebih dahulu.'
                ], 403);
            }

            $penilaianController = new PenilaianController($this->patientService, $this->pointService);
            $result = $penilaianController->prosesPenilaian($selectedPoints, $patientId);

            if (!$result) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak dapat memproses data penilaian'
                ], 400);
            }

            $patient = $result['patient'];
            $latestAntro = $result['latestAntro'];
            $interpretasiGizi = $result['interpretasiGizi'];

            // Ambil data pasien
            $images = [];
            $kurvaTableSettings = DB::table('kurva_table_settings')->select('judul', 'nama_tabel')->get()->keyBy('nama_tabel')->toArray();

            for ($i = 1; $i <= 12; $i++) {
                $path = public_path("img-public/kurva/chart-{$patientId}-table{$i}.png");

                if (!file_exists($path)) {
                    Log::warning("Gambar chart-{$patientId}-table{$i}.png tidak ditemukan di kurva/");
                    continue;
                }

                $images[] = ("img-public/kurva/chart-{$patientId}-table{$i}.png");
            }

            if (count($images) === 0) {
                Log::error("Tidak ada gambar yang ditemukan untuk PDF.");
                return response()->json(['status' => 'error', 'message' => 'Tidak ada gambar'], 404);
            }

            // Get ads image if available
            $adsImage = null;
            $adsSetting = Cache::rememberForever('pdf_ads', function () {
                return DB::table('lp_settings')->where('key', 'ads')->first();
            });

            if ($adsSetting && !empty($adsSetting->value)) {
                $adsPath = public_path($adsSetting->value);
                if (file_exists($adsPath)) {
                    $adsImage = $adsSetting->value;
                    Log::info("Ads image found and will be included in PDF: {$adsImage}");
                    // Add ads image to the images array to be processed in the loop
                    $images[] = $adsSetting->value;
                }
            }

            // Extract just the IDs from the selectedPoints data structure
            $pointIds = [];
            if (is_array($selectedPoints)) {
                // Check if we have a nested data structure
                if (isset($selectedPoints['data']) && is_array($selectedPoints['data'])) {
                    foreach ($selectedPoints['data'] as $point) {
                        if (isset($point['id'])) {
                            $pointIds[] = $point['id'];
                        }
                    }
                } else {
                    // Try to extract IDs if they're directly in the array
                    foreach ($selectedPoints as $point) {
                        if (is_array($point) && isset($point['id'])) {
                            $pointIds[] = $point['id'];
                        } elseif (is_numeric($point)) {
                            $pointIds[] = $point;
                        }
                    }
                }
            } else if (is_numeric($selectedPoints)) {
                $pointIds = [$selectedPoints];
            }

            $antros = DB::table('antro_patients')
                ->whereIn('antro_patients.id', $pointIds)
                ->where(function ($query) {
                    $query->whereNotNull('antro_patients.notes')
                        ->where('antro_patients.notes', '!=', '');
                })
                ->leftJoin('users', 'antro_patients.created_by', '=', 'users.id')
                ->select('antro_patients.tgl_periksa', 'antro_patients.notes', 'users.name as created_by')
                ->orderBy('antro_patients.tgl_periksa', 'desc')
                ->get();

            $pdf = PDF::loadView('pdf.kurva', [
                'images' => $images,
                'patient' => $patient,
                'kurvaTableSettings' => $kurvaTableSettings,
                'logo' => $this->logo,
                'antros' => $antros,
                'latestAntro' => $latestAntro,
                'interpretasiGizi' => $interpretasiGizi,
                'superAdmin' => $this->superAdmin,
                'pdfSetting' => $this->pdfSetting,
            ])
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'enable_remote' => false,
                    'chroot' => public_path('img-public'),
                    'defaultPaperSize' => 'a4',
                    'margin_top' => 0,      // dalam satuan milimeter
                    'margin_right' => 0,
                    'margin_bottom' => 0,
                    'margin_left' => 0,
                ]);


            // Format file name
            $filename = $this->generateFileName($patient, $latestAntro);

            Storage::disk('public')->put("kurva/{$filename}", $pdf->output());

            Log::info("PDF berhasil dibuat dan disimpan di storage/kurva/{$filename}");

            // Penerima email
            // Jika pasien tidak punya email, kirim ke admin
            // Jika pasien punya email, kirim ke pasien
            $penerima = [
                'nama' => !$patient->email ? Auth::user()->name : $patient->nama,
                'email' => $patient->email ?? Auth::user()->email,
            ];

            // Jika sender name tidak ada, gunakan default
            $senderName = $senderName ?? $this->senderNameDefault;

            if (!$patient->email) {
                $penerima['email'] = Auth::user()->email;
            }

            // Dispatch job to queue for sending email
            \App\Jobs\SendPdfEmail::dispatch(
                $patient,
                $filename,
                $senderName,
                $penerima['email'],
                $penerima['nama'],
                $images
            );

            // Kurangi poin setelah PDF berhasil dibuat
            if ($hasHeader && $hasSenderName) {
                $this->pointService->usage(
                    $context['user_id'],
                    $context['instansi_id'],
                    ($pointSetting->points + $pointSettingHeader->points + $pointSettingSenderName->points),
                    'Kirim Grafik PDF via Email + Header + Custom Sender Name',
                    $pointSetting->id,
                    $patientId
                );
            } elseif ($hasHeader) {
                $this->pointService->usage(
                    $context['user_id'],
                    $context['instansi_id'],
                    ($pointSetting->points + $pointSettingHeader->points),
                    'Kirim Grafik PDF via Email + Header',
                    $pointSetting->id,
                    $patientId
                );
            } elseif ($hasSenderName) {
                $this->pointService->usage(
                    $context['user_id'],
                    $context['instansi_id'],
                    ($pointSetting->points + $pointSettingSenderName->points),
                    'Kirim Grafik PDF via Email + Sender Name',
                    $pointSetting->id,
                    $patientId
                );
            } else {
                $this->pointService->usage(
                    $context['user_id'],
                    $context['instansi_id'],
                    $pointSetting->points,
                    'Kirim Grafik PDF via Email',
                    $pointSetting->id,
                    $patientId
                );
            }

            if ($request->has('skip_confirmation') && $request->skip_confirmation) {
                Cookie::queue('skip_confirm', 'true', 60 * 24 * 30); // 30 hari
            }

            return response()->json([
                'status' => 'success',
                'message' => "PDF sedang diproses dan akan dikirim ke email! \n Silahkan cek email di Inbox, Folder Junk Mail atau Folder Spam dalam beberapa menit."
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error saat generate PDF: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Gagal membuat PDF'], 500);
        }
    }

    public function generateAndSendCustomPdf(Request $request, $patientId)
    {
        try {
            // Extract selected points from request
            $selectedPoints = $request->input('selectedPoints') ?? [];
            $senderName = $request->input('displayName') ?? null;
            $emailAddress = $request->input('emailAddress') ?? null;

            // Log the received data for debugging
            Log::info("Received selectedPoints data:", ['data' => $selectedPoints, 'senderName' => $senderName, 'emailAddress' => $emailAddress]);

            if (empty($selectedPoints)) {
                Log::error("Tidak ada data yang dipilih untuk download PDF.");
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak ada data point yang dipilih'
                ], 400);
            }

            // Get point setting for download PDF
            $context = getInstansiOrUserContext(Auth::user());
            $pointSetting = $this->pointService->findSettingByName('PUSH-EMAIL-GRAFIK');
            $pointSettingHeader = $this->pointService->findSettingByName('TAMBAH-HEADER');
            $pointSettingSenderName = $this->pointService->findSettingByName('EMAIL-CUSTOM');
            $pointSettingCustomEmail = $this->pointService->findSettingByName('EMAIL-CUSTOM');

            if (Auth::user()->isSupportHeader()) {
                $hasHeader = false;
            } else {
                $hasHeader = Auth::user()->is_nakes ? Auth::user()->instansi->header : Auth::user()->header;
            }
            $hasSenderName = $senderName ? true : false;

            // Check if using custom email (not patient email or user email)
            $patient = \App\Models\Patient::findOrFail($patientId);
            $isCustomEmail = false;
            if (
                $emailAddress &&
                $emailAddress !== $patient->email &&
                $emailAddress !== Auth::user()->email
            ) {
                $isCustomEmail = true;
            }

            // Calculate total points needed
            $totalPoints = $pointSetting->points;
            if ($hasHeader) {
                $totalPoints += $pointSettingHeader->points;
            }
            if ($hasSenderName) {
                $totalPoints += $pointSettingSenderName->points;
            }
            if ($isCustomEmail) {
                $totalPoints += $pointSettingCustomEmail->points;
            }

            // Check jika user mempunyai point cukup
            $isEnough = $this->pointService->isPointEnough(
                $context['user_id'],
                $context['instansi_id'],
                $totalPoints
            );

            if (!$isEnough) {
                Log::error("Poin tidak cukup untuk download PDF. User ID: {$context['user_id']}");
                return response()->json([
                    'status' => 'error',
                    'message' => 'Poin Anda tidak cukup atau masa aktif poin Anda sudah habis untuk download PDF! Silahkan top up poin terlebih dahulu.'
                ], 403);
            }

            $penilaianController = new PenilaianController($this->patientService, $this->pointService);
            $result = $penilaianController->prosesPenilaian($selectedPoints, $patientId);

            if (!$result) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak dapat memproses data penilaian'
                ], 400);
            }

            $latestAntro = $result['latestAntro'];
            $interpretasiGizi = $result['interpretasiGizi'];

            // Ambil data pasien
            $images = [];
            $kurvaTableSettings = DB::table('kurva_table_settings')->select('judul', 'nama_tabel')->get()->keyBy('nama_tabel')->toArray();

            for ($i = 1; $i <= 12; $i++) {
                $path = public_path("img-public/kurva/chart-{$patientId}-table{$i}.png");

                if (!file_exists($path)) {
                    Log::warning("Gambar chart-{$patientId}-table{$i}.png tidak ditemukan di kurva/");
                    continue;
                }

                $images[] = ("img-public/kurva/chart-{$patientId}-table{$i}.png");
            }

            if (count($images) === 0) {
                Log::error("Tidak ada gambar yang ditemukan untuk PDF.");
                return response()->json(['status' => 'error', 'message' => 'Tidak ada gambar'], 404);
            }

            // Get ads image if available
            $adsImage = null;
            $adsSetting = Cache::rememberForever('pdf_ads', function () {
                return DB::table('lp_settings')->where('key', 'ads')->first();
            });

            if ($adsSetting && !empty($adsSetting->value)) {
                $adsPath = public_path($adsSetting->value);
                if (file_exists($adsPath)) {
                    $adsImage = $adsSetting->value;
                    Log::info("Ads image found and will be included in PDF: {$adsImage}");
                    // Add ads image to the images array to be processed in the loop
                    $images[] = $adsSetting->value;
                }
            }

            // Extract just the IDs from the selectedPoints data structure
            $pointIds = [];
            if (is_array($selectedPoints)) {
                // Check if we have a nested data structure
                if (isset($selectedPoints['data']) && is_array($selectedPoints['data'])) {
                    foreach ($selectedPoints['data'] as $point) {
                        if (isset($point['id'])) {
                            $pointIds[] = $point['id'];
                        }
                    }
                } else {
                    // Try to extract IDs if they're directly in the array
                    foreach ($selectedPoints as $point) {
                        if (is_array($point) && isset($point['id'])) {
                            $pointIds[] = $point['id'];
                        } elseif (is_numeric($point)) {
                            $pointIds[] = $point;
                        }
                    }
                }
            } else if (is_numeric($selectedPoints)) {
                $pointIds = [$selectedPoints];
            }

            $antros = DB::table('antro_patients')
                ->whereIn('antro_patients.id', $pointIds)
                ->where(function ($query) {
                    $query->whereNotNull('antro_patients.notes')
                        ->where('antro_patients.notes', '!=', '');
                })
                ->leftJoin('users', 'antro_patients.created_by', '=', 'users.id')
                ->select('antro_patients.tgl_periksa', 'antro_patients.notes', 'users.name as created_by')
                ->orderBy('antro_patients.tgl_periksa', 'desc')
                ->get();

            $pdf = PDF::loadView('pdf.kurva', [
                'images' => $images,
                'patient' => $patient,
                'kurvaTableSettings' => $kurvaTableSettings,
                'logo' => $this->logo,
                'antros' => $antros,
                'latestAntro' => $latestAntro,
                'interpretasiGizi' => $interpretasiGizi,
                'superAdmin' => $this->superAdmin,
                'pdfSetting' => $this->pdfSetting,
            ])
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'enable_remote' => false,
                    'chroot' => public_path('img-public'),
                    'defaultPaperSize' => 'a4',
                    'margin_top' => 0,      // dalam satuan milimeter
                    'margin_right' => 0,
                    'margin_bottom' => 0,
                    'margin_left' => 0,
                ]);


            // Format file name
            $filename = $this->generateFileName($patient, $latestAntro);

            Storage::disk('public')->put("kurva/{$filename}", $pdf->output());

            Log::info("PDF berhasil dibuat dan disimpan di storage/kurva/{$filename}");

            // Penerima email
            // Use custom email address if provided, otherwise use patient/admin email
            $penerima = [
                'nama' => $emailAddress ? 'Penerima' : (!$patient->email ? Auth::user()->name : $patient->nama),
                'email' => $emailAddress ?? ($patient->email ?? Auth::user()->email),
            ];

            // Jika sender name tidak ada, gunakan default
            $senderName = $senderName ?? $this->senderNameDefault;

            // Dispatch job to queue for sending email
            \App\Jobs\SendPdfEmail::dispatch(
                $patient,
                $filename,
                $senderName,
                $penerima['email'],
                $penerima['nama'],
                $images
            );

            // Kurangi poin setelah PDF berhasil dibuat - simplified logic using total points
            $usageDescription = 'Kirim Grafik PDF via Email';
            if ($hasHeader) {
                $usageDescription .= ' + Header';
            }
            if ($hasSenderName) {
                $usageDescription .= ' + Custom Sender Name';
            }
            if ($isCustomEmail) {
                $usageDescription .= ' + Custom Email';
            }

            $this->pointService->usage(
                $context['user_id'],
                $context['instansi_id'],
                $totalPoints,
                $usageDescription,
                $pointSetting->id,
                $patientId
            );

            if ($request->has('skip_confirmation') && $request->skip_confirmation) {
                Cookie::queue('skip_confirm', 'true', 60 * 24 * 30); // 30 hari
            }

            return response()->json([
                'status' => 'success',
                'message' => "PDF sedang diproses dan akan dikirim ke email! \n Silahkan cek email di Inbox, Folder Junk Mail atau Folder Spam dalam beberapa menit."
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error saat generate PDF: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Gagal membuat PDF'], 500);
        }
    }

    public function generateAndSendWa(Request $request, $patientId)
    {
        try {
            // Extract selected points from request
            $selectedPoints = $request->input('selectedPoints') ?? [];
            $whatsappNumber = $request->input('whatsappNumber');

            // Check number
            $isValidNumber = $this->whatsappService->checkNumber($whatsappNumber);

            if (!$isValidNumber) {
                Log::error("Nomor WhatsApp {$whatsappNumber} tidak valid.");
                return response()->json(['status' => 'error', 'message' => "Nomor WhatsApp {$whatsappNumber} tidak terdaftar"], 400);
            }

            if (empty($selectedPoints)) {
                Log::error("Tidak ada data yang dipilih untuk kirim PDF via WhatsApp.");
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak ada data point yang dipilih'
                ], 400);
            }

            // Get point setting for WhatsApp PDF sending
            $context = getInstansiOrUserContext(Auth::user());
            $pointSetting = $this->pointService->findSettingByName('PUSH-WHATSAPP-GRAFIK');
            $pointSettingCustomWa = $this->pointService->findSettingByName('NO-WA-CUSTOM');
            $pointSettingHeader = $this->pointService->findSettingByName('TAMBAH-HEADER');

            if (Auth::user()->isSupportHeader()) {
                $hasHeader = false;
            } else {
                $hasHeader = Auth::user()->is_nakes ? Auth::user()->instansi->header : Auth::user()->header;
            }

            // Process the assessment data first to get patient data
            $penilaianController = new PenilaianController($this->patientService, $this->pointService);
            $result = $penilaianController->prosesPenilaian($selectedPoints, $patientId);

            if (!$result) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak dapat memproses data penilaian'
                ], 400);
            }

            $patient = $result['patient'];

            // Determine if WhatsApp number is custom (not belonging to patient or user)
            $isCustomNumber = true;
            $pointsNeeded = $pointSetting->points;
            $pointUsageDescription = 'Kirim Grafik PDF via WhatsApp';

            // Check if it's patient's number
            if ($patient->no_wa && $patient->no_wa === $whatsappNumber) {
                $isCustomNumber = false;
                Log::info("Using patient's WhatsApp number");
            }
            // Check if it's user's number
            else if (Auth::user()->phone && $this->normalizePhoneNumber(Auth::user()->phone) === $whatsappNumber) {
                $isCustomNumber = false;

                Log::info("Using user's WhatsApp number", [
                    'phone' => $this->normalizePhoneNumber(Auth::user()->phone),
                    'whatsappNumber' => $this->normalizePhoneNumber($whatsappNumber),
                ]);
            }

            // Add custom WA points if using custom number
            if ($isCustomNumber) {
                $pointsNeeded += $pointSettingCustomWa->points;
                $pointUsageDescription .= ' + Custom WA';
                Log::info("Using custom WhatsApp number, additional points required");
            }

            // Add header points if applicable
            if ($hasHeader) {
                $pointsNeeded += $pointSettingHeader->points;
                $pointUsageDescription .= ' + Header';
            }

            // Check if user has enough points
            $isEnough = $this->pointService->isPointEnough(
                $context['user_id'],
                $context['instansi_id'],
                $pointsNeeded
            );

            if (!$isEnough) {
                Log::error("Poin tidak cukup untuk kirim PDF via WhatsApp. User ID: {$context['user_id']}");
                return response()->json([
                    'status' => 'error',
                    'message' => 'Poin Anda tidak cukup atau masa aktif poin Anda sudah habis! Silahkan top up poin terlebih dahulu.'
                ], 403);
            }

            $latestAntro = $result['latestAntro'];
            $interpretasiGizi = $result['interpretasiGizi'];

            // Get patient data
            $images = [];
            $kurvaTableSettings = DB::table('kurva_table_settings')
                ->select('judul', 'nama_tabel')
                ->get()
                ->keyBy('nama_tabel')
                ->toArray();

            // Collect chart images
            for ($i = 1; $i <= 12; $i++) {
                $path = public_path("img-public/kurva/chart-{$patientId}-table{$i}.png");

                if (!file_exists($path)) {
                    Log::warning("Gambar chart-{$patientId}-table{$i}.png tidak ditemukan di kurva/");
                    continue;
                }

                $images[] = ("img-public/kurva/chart-{$patientId}-table{$i}.png");
            }

            if (count($images) === 0) {
                Log::error("Tidak ada gambar yang ditemukan untuk PDF.");
                return response()->json(['status' => 'error', 'message' => 'Tidak ada gambar'], 404);
            }

            // Get ads image if available
            $adsImage = null;
            $adsSetting = Cache::rememberForever('pdf_ads', function () {
                return DB::table('lp_settings')->where('key', 'ads')->first();
            });

            if ($adsSetting && !empty($adsSetting->value)) {
                $adsPath = public_path($adsSetting->value);
                if (file_exists($adsPath)) {
                    $adsImage = $adsSetting->value;
                    Log::info("Ads image found and will be included in PDF: {$adsImage}");
                    // Add ads image to the images array to be processed in the loop
                    $images[] = $adsSetting->value;
                }
            }

            // Extract just the IDs from the selectedPoints data structure
            $pointIds = [];
            if (is_array($selectedPoints)) {
                // Check if we have a nested data structure
                if (isset($selectedPoints['data']) && is_array($selectedPoints['data'])) {
                    foreach ($selectedPoints['data'] as $point) {
                        if (isset($point['id'])) {
                            $pointIds[] = $point['id'];
                        }
                    }
                } else {
                    // Try to extract IDs if they're directly in the array
                    foreach ($selectedPoints as $point) {
                        if (is_array($point) && isset($point['id'])) {
                            $pointIds[] = $point['id'];
                        } elseif (is_numeric($point)) {
                            $pointIds[] = $point;
                        }
                    }
                }
            } else if (is_numeric($selectedPoints)) {
                $pointIds = [$selectedPoints];
            }

            $antros = DB::table('antro_patients')
                ->whereIn('antro_patients.id', $pointIds)
                ->where(function ($query) {
                    $query->whereNotNull('antro_patients.notes')
                        ->where('antro_patients.notes', '!=', '');
                })
                ->leftJoin('users', 'antro_patients.created_by', '=', 'users.id')
                ->select('antro_patients.tgl_periksa', 'antro_patients.notes', 'users.name as created_by')
                ->orderBy('antro_patients.tgl_periksa', 'desc')
                ->get();

            $pdf = PDF::loadView('pdf.kurva', [
                'images' => $images,
                'patient' => $patient,
                'kurvaTableSettings' => $kurvaTableSettings,
                'logo' => $this->logo,
                'antros' => $antros,
                'latestAntro' => $latestAntro,
                'interpretasiGizi' => $interpretasiGizi,
                'superAdmin' => $this->superAdmin,
                'pdfSetting' => $this->pdfSetting,
            ])
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'enable_remote' => false,
                    'chroot' => public_path('img-public'),
                    'defaultPaperSize' => 'a4',
                    'margin_top' => 8,
                    'margin_right' => 8,
                    'margin_bottom' => 8,
                    'margin_left' => 8,
                ]);

            // Format file name and save PDF - using URL-safe filename
            $filename = $this->generateFileNameWhatsapp($patient, $latestAntro);
            Storage::disk('public')->put("kurva/{$filename}", $pdf->output());

            // Make the file publicly accessible
            Storage::disk('public')->setVisibility("kurva/{$filename}", 'public');

            // Ensure proper URL encoding for the filename
            $encodedFilename = rawurlencode($filename);
            $fileUrl = config('app.url') . '/storage/kurva/' . $encodedFilename;

            Log::info("Generated PDF URL for WhatsApp:", ['url' => $fileUrl]);

            // Queue the WhatsApp messaging job
            \App\Jobs\SendPdfWhatsapp::dispatch(
                $patient,
                $filename,
                $whatsappNumber,
                $fileUrl,
                $images
            );

            // Reduce points based on usage scenario
            $this->pointService->usage(
                $context['user_id'],
                $context['instansi_id'],
                $pointsNeeded,
                $pointUsageDescription,
                $pointSetting->id,
                $patientId
            );

            return response()->json([
                'status' => 'success',
                'message' => "PDF sedang diproses dan akan dikirim ke WhatsApp {$whatsappNumber}!"
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error saat kirim PDF via WhatsApp: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Gagal mengirim PDF: Silahkan hubungi admin jika ada pertanyaan!'], 500);
        }
    }

    // Super Admin
    public function generatePdfSuperAdmin(Request $request, $patientId)
    {
        try {
            // Extract selected points from request
            $selectedPoints = $request->input('selectedPoints') ?? [];

            // Log the received data for debugging
            Log::info("Received selectedPoints data:", ['data' => $selectedPoints]);

            if (empty($selectedPoints)) {
                Log::error("Tidak ada data yang dipilih untuk download PDF.");
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak ada data point yang dipilih'
                ], 400);
            }

            $penilaianController = new PenilaianController($this->patientService, $this->pointService);
            $result = $penilaianController->prosesPenilaian($selectedPoints, $patientId);

            if (!$result) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak dapat memproses data penilaian'
                ], 400);
            }

            $patient = $result['patient'];
            $latestAntro = $result['latestAntro'];
            $interpretasiGizi = $result['interpretasiGizi'];

            $images = [];
            $kurvaTableSettings = DB::table('kurva_table_settings')->select('judul', 'nama_tabel')->get()->keyBy('nama_tabel')->toArray();

            for ($i = 1; $i <= 12; $i++) {
                $path = public_path("img-public/kurva/chart-{$patientId}-table{$i}.png");

                if (!file_exists($path)) {
                    Log::warning("Gambar chart-{$patientId}-table{$i}.png tidak ditemukan di kurva/");
                    continue;
                }

                $images[] = ("img-public/kurva/chart-{$patientId}-table{$i}.png");
            }

            if (count($images) === 0) {
                Log::error("Tidak ada gambar yang ditemukan untuk PDF.");
                return response()->json(['status' => 'error', 'message' => 'Tidak ada gambar'], 404);
            }

            // Get ads image if available
            $adsImage = null;
            $adsSetting = Cache::rememberForever('pdf_ads', function () {
                return DB::table('lp_settings')->where('key', 'ads')->first();
            });;
            if ($adsSetting && !empty($adsSetting->value)) {
                $adsPath = public_path($adsSetting->value);
                if (file_exists($adsPath)) {
                    $adsImage = $adsSetting->value;
                    Log::info("Ads image found and will be included in PDF: {$adsImage}");
                    // Add ads image to the images array to be processed in the loop
                    $images[] = $adsSetting->value;
                }
            }

            // Extract just the IDs from the selectedPoints data structure
            $pointIds = [];
            if (is_array($selectedPoints)) {
                // Check if we have a nested data structure
                if (isset($selectedPoints['data']) && is_array($selectedPoints['data'])) {
                    foreach ($selectedPoints['data'] as $point) {
                        if (isset($point['id'])) {
                            $pointIds[] = $point['id'];
                        }
                    }
                } else {
                    // Try to extract IDs if they're directly in the array
                    foreach ($selectedPoints as $point) {
                        if (is_array($point) && isset($point['id'])) {
                            $pointIds[] = $point['id'];
                        } elseif (is_numeric($point)) {
                            $pointIds[] = $point;
                        }
                    }
                }
            } else if (is_numeric($selectedPoints)) {
                $pointIds = [$selectedPoints];
            }

            $antros = DB::table('antro_patients')
                ->whereIn('antro_patients.id', $pointIds)
                ->where(function ($query) {
                    $query->whereNotNull('antro_patients.notes')
                        ->where('antro_patients.notes', '!=', '');
                })
                ->leftJoin('users', 'antro_patients.created_by', '=', 'users.id')
                ->select('antro_patients.tgl_periksa', 'antro_patients.notes', 'users.name as created_by')
                ->orderBy('antro_patients.tgl_periksa', 'desc')
                ->get();

            $pdf = PDF::loadView('pdf.kurva', [
                'images' => $images,
                'patient' => $patient,
                'kurvaTableSettings' => $kurvaTableSettings,
                'logo' => $this->logo,
                'antros' => $antros,
                'latestAntro' => $latestAntro,
                'interpretasiGizi' => $interpretasiGizi,
                'superAdmin' => $this->superAdmin,
                'pdfSetting' => $this->pdfSetting,
            ])
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'enable_remote' => false,
                    'chroot' => public_path('img-public'),
                    'defaultPaperSize' => 'a4',
                    'margin_top' => 8,      // dalam satuan milimeter
                    'margin_right' => 8,
                    'margin_bottom' => 8,
                    'margin_left' => 8,
                ]);


            // Format file name
            $filename = $this->generateFileName($patient, $latestAntro);

            Storage::disk('public')->put("kurva/{$filename}", $pdf->output());

            Log::info("PDF berhasil dibuat dan disimpan di storage/kurva/{$filename}");

            // Hapus gambar setelah PDF dibuat
            foreach ($images as $imgPath) {
                // Skip deletion if this is the ads image
                if (strpos($imgPath, 'ads_') !== false || strpos($imgPath, 'lp-setting/ads_') !== false) {
                    Log::info("Skipping deletion of ads image: {$imgPath}");
                    continue;
                }

                $fullPath = public_path($imgPath);
                if (File::exists($fullPath)) {
                    File::delete($fullPath);
                    Log::info("Gambar {$imgPath} berhasil dihapus.");
                }
            }

            // Hapus file PDF setelah dikirim
            $pathToDelete = storage_path("app/public/kurva/{$filename}");

            if (File::exists($pathToDelete)) {
                File::delete($pathToDelete);
                Log::info("File PDF {$filename} berhasil dihapus setelah dikirim.");
            }

            return response($pdf->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } catch (\Exception $e) {
            Log::error("Error saat generate PDF: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Gagal membuat PDF'], 500);
        }
    }

    public function generateAndSendPdfSuperAdmin(Request $request, $patientId)
    {
        try {
            // Extract selected points from request
            $selectedPoints = $request->input('selectedPoints') ?? [];
            $senderName = $request->input('displayName') ?? $this->senderNameDefault;

            // Log the received data for debugging
            Log::info("Received selectedPoints data:", ['data' => $selectedPoints]);

            if (empty($selectedPoints)) {
                Log::error("Tidak ada data yang dipilih untuk download PDF.");
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak ada data point yang dipilih'
                ], 400);
            }

            $penilaianController = new PenilaianController($this->patientService, $this->pointService);
            $result = $penilaianController->prosesPenilaian($selectedPoints, $patientId);

            if (!$result) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak dapat memproses data penilaian'
                ], 400);
            }

            $patient = $result['patient'];
            $latestAntro = $result['latestAntro'];
            $interpretasiGizi = $result['interpretasiGizi'];

            // Ambil data pasien
            $images = [];
            $kurvaTableSettings = DB::table('kurva_table_settings')->select('judul', 'nama_tabel')->get()->keyBy('nama_tabel')->toArray();

            for ($i = 1; $i <= 12; $i++) {
                $path = public_path("img-public/kurva/chart-{$patientId}-table{$i}.png");

                if (!file_exists($path)) {
                    Log::warning("Gambar chart-{$patientId}-table{$i}.png tidak ditemukan di kurva/");
                    continue;
                }

                $images[] = ("img-public/kurva/chart-{$patientId}-table{$i}.png");
            }

            if (count($images) === 0) {
                Log::error("Tidak ada gambar yang ditemukan untuk PDF.");
                return response()->json(['status' => 'error', 'message' => 'Tidak ada gambar'], 404);
            }

            // Get ads image if available
            $adsImage = null;
            $adsSetting = Cache::rememberForever('pdf_ads', function () {
                return DB::table('lp_settings')->where('key', 'ads')->first();
            });

            if ($adsSetting && !empty($adsSetting->value)) {
                $adsPath = public_path($adsSetting->value);
                if (file_exists($adsPath)) {
                    $adsImage = $adsSetting->value;
                    Log::info("Ads image found and will be included in PDF: {$adsImage}");
                    // Add ads image to the images array to be processed in the loop
                    $images[] = $adsSetting->value;
                }
            }

            // Extract just the IDs from the selectedPoints data structure
            $pointIds = [];
            if (is_array($selectedPoints)) {
                // Check if we have a nested data structure
                if (isset($selectedPoints['data']) && is_array($selectedPoints['data'])) {
                    foreach ($selectedPoints['data'] as $point) {
                        if (isset($point['id'])) {
                            $pointIds[] = $point['id'];
                        }
                    }
                } else {
                    // Try to extract IDs if they're directly in the array
                    foreach ($selectedPoints as $point) {
                        if (is_array($point) && isset($point['id'])) {
                            $pointIds[] = $point['id'];
                        } elseif (is_numeric($point)) {
                            $pointIds[] = $point;
                        }
                    }
                }
            } else if (is_numeric($selectedPoints)) {
                $pointIds = [$selectedPoints];
            }

            $antros = DB::table('antro_patients')
                ->whereIn('antro_patients.id', $pointIds)
                ->where(function ($query) {
                    $query->whereNotNull('antro_patients.notes')
                        ->where('antro_patients.notes', '!=', '');
                })
                ->leftJoin('users', 'antro_patients.created_by', '=', 'users.id')
                ->select('antro_patients.tgl_periksa', 'antro_patients.notes', 'users.name as created_by')
                ->orderBy('antro_patients.tgl_periksa', 'desc')
                ->get();

            $pdf = PDF::loadView('pdf.kurva', [
                'images' => $images,
                'patient' => $patient,
                'kurvaTableSettings' => $kurvaTableSettings,
                'logo' => $this->logo,
                'antros' => $antros,
                'latestAntro' => $latestAntro,
                'interpretasiGizi' => $interpretasiGizi,
                'superAdmin' => $this->superAdmin,
                'pdfSetting' => $this->pdfSetting,
            ])
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'enable_remote' => false,
                    'chroot' => public_path('img-public'),
                    'defaultPaperSize' => 'a4',
                    'margin_top' => 8,      // dalam satuan milimeter
                    'margin_right' => 8,
                    'margin_bottom' => 8,
                    'margin_left' => 8,
                ]);

            // Format file name
            $filename = $this->generateFileName($patient, $latestAntro);

            Storage::disk('public')->put("kurva/{$filename}", $pdf->output());

            Log::info("PDF berhasil dibuat dan disimpan di storage/kurva/{$filename}");

            // Penerima email
            // Jika pasien tidak punya email, kirim ke admin
            // Jika pasien punya email, kirim ke pasien
            $penerima = [
                'nama' => !$patient->email ? Auth::user()->name : $patient->nama,
                'email' => $patient->email ?? Auth::user()->email,
            ];

            // Dispatch job to queue for sending email
            \App\Jobs\SendPdfEmail::dispatch(
                $patient,
                $filename,
                $senderName,
                $penerima['email'],
                $penerima['nama'],
                $images
            );

            return response()->json([
                'status' => 'success',
                'message' => "PDF sedang diproses dan akan dikirim ke email! \n Silahkan cek email di Inbox, Folder Junk Mail atau Folder Spam dalam beberapa menit."
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error saat generate PDF: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Gagal membuat PDF'], 500);
        }
    }

    public function generateAndSendWaSuperAdmin(Request $request, $patientId)
    {
        try {
            // Extract selected points from request
            $selectedPoints = $request->input('selectedPoints') ?? [];
            $whatsappNumber = $request->input('whatsappNumber');

            // Check number
            $isValidNumber = $this->whatsappService->checkNumber($whatsappNumber);

            if (!$isValidNumber) {
                Log::error("Nomor WhatsApp {$whatsappNumber} tidak valid.");
                return response()->json(['status' => 'error', 'message' => "Nomor WhatsApp {$whatsappNumber} tidak terdaftar"], 400);
            }

            if (empty($selectedPoints)) {
                Log::error("Tidak ada data yang dipilih untuk kirim PDF via WhatsApp.");
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak ada data point yang dipilih'
                ], 400);
            }

            $hasHeader = Auth::user()->is_nakes ? Auth::user()->instansi->header : Auth::user()->header;

            // Process the assessment data first to get patient data
            $penilaianController = new PenilaianController($this->patientService, $this->pointService);
            $result = $penilaianController->prosesPenilaian($selectedPoints, $patientId);

            if (!$result) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak dapat memproses data penilaian'
                ], 400);
            }

            $patient = $result['patient'];
            $latestAntro = $result['latestAntro'];
            $interpretasiGizi = $result['interpretasiGizi'];

            // Get patient data
            $images = [];
            $kurvaTableSettings = DB::table('kurva_table_settings')
                ->select('judul', 'nama_tabel')
                ->get()
                ->keyBy('nama_tabel')
                ->toArray();

            // Collect chart images
            for ($i = 1; $i <= 12; $i++) {
                $path = public_path("img-public/kurva/chart-{$patientId}-table{$i}.png");

                if (!file_exists($path)) {
                    Log::warning("Gambar chart-{$patientId}-table{$i}.png tidak ditemukan di kurva/");
                    continue;
                }

                $images[] = ("img-public/kurva/chart-{$patientId}-table{$i}.png");
            }

            if (count($images) === 0) {
                Log::error("Tidak ada gambar yang ditemukan untuk PDF.");
                return response()->json(['status' => 'error', 'message' => 'Tidak ada gambar'], 404);
            }

            // Get ads image if available
            $adsImage = null;
            $adsSetting = Cache::rememberForever('pdf_ads', function () {
                return DB::table('lp_settings')->where('key', 'ads')->first();
            });

            if ($adsSetting && !empty($adsSetting->value)) {
                $adsPath = public_path($adsSetting->value);
                if (file_exists($adsPath)) {
                    $adsImage = $adsSetting->value;
                    Log::info("Ads image found and will be included in PDF: {$adsImage}");
                    // Add ads image to the images array to be processed in the loop
                    $images[] = $adsSetting->value;
                }
            }

            // Extract just the IDs from the selectedPoints data structure
            $pointIds = [];
            if (is_array($selectedPoints)) {
                // Check if we have a nested data structure
                if (isset($selectedPoints['data']) && is_array($selectedPoints['data'])) {
                    foreach ($selectedPoints['data'] as $point) {
                        if (isset($point['id'])) {
                            $pointIds[] = $point['id'];
                        }
                    }
                } else {
                    // Try to extract IDs if they're directly in the array
                    foreach ($selectedPoints as $point) {
                        if (is_array($point) && isset($point['id'])) {
                            $pointIds[] = $point['id'];
                        } elseif (is_numeric($point)) {
                            $pointIds[] = $point;
                        }
                    }
                }
            } else if (is_numeric($selectedPoints)) {
                $pointIds = [$selectedPoints];
            }

            $antros = DB::table('antro_patients')
                ->whereIn('antro_patients.id', $pointIds)
                ->where(function ($query) {
                    $query->whereNotNull('antro_patients.notes')
                        ->where('antro_patients.notes', '!=', '');
                })
                ->leftJoin('users', 'antro_patients.created_by', '=', 'users.id')
                ->select('antro_patients.tgl_periksa', 'antro_patients.notes', 'users.name as created_by')
                ->orderBy('antro_patients.tgl_periksa', 'desc')
                ->get();

            $pdf = PDF::loadView('pdf.kurva', [
                'images' => $images,
                'patient' => $patient,
                'kurvaTableSettings' => $kurvaTableSettings,
                'logo' => $this->logo,
                'antros' => $antros,
                'latestAntro' => $latestAntro,
                'interpretasiGizi' => $interpretasiGizi,
                'superAdmin' => $this->superAdmin,
                'pdfSetting' => $this->pdfSetting,
            ])
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'enable_remote' => false,
                    'chroot' => public_path('img-public'),
                    'defaultPaperSize' => 'a4',
                    'margin_top' => 8,
                    'margin_right' => 8,
                    'margin_bottom' => 8,
                    'margin_left' => 8,
                ]);

            // Format file name and save PDF - using URL-safe filename
            $filename = $this->generateFileNameWhatsapp($patient, $latestAntro);
            Storage::disk('public')->put("kurva/{$filename}", $pdf->output());

            // Make the file publicly accessible
            Storage::disk('public')->setVisibility("kurva/{$filename}", 'public');

            // Ensure proper URL encoding for the filename
            $encodedFilename = rawurlencode($filename);
            $fileUrl = config('app.url') . '/storage/kurva/' . $encodedFilename;

            Log::info("Generated PDF URL for WhatsApp:", ['url' => $fileUrl]);

            // Queue the WhatsApp messaging job
            \App\Jobs\SendPdfWhatsapp::dispatch(
                $patient,
                $filename,
                $whatsappNumber,
                $fileUrl,
                $images
            );

            return response()->json([
                'status' => 'success',
                'message' => "PDF sedang diproses dan akan dikirim ke WhatsApp {$whatsappNumber}!"
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error saat kirim PDF via WhatsApp: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Gagal mengirim PDF: Silahkan hubungi admin jika ada pertanyaan!'], 500);
        }
    }

    private function generateFileName($patient, $latestAntro)
    {
        $patientName = substr($patient->nama, 0, 10); // Limit patient name to 10 chars
        [$tahunUs, $bulanUs, $hariUs] = convertDaysToYear(
            $latestAntro->tgl_periksa,
            $latestAntro->total_usia_hari ?? 0,
        );
        $usiaSebenarnya = $tahunUs . ' th ' . $bulanUs . ' bl ' . $hariUs . ' hr';
        $timeDownload = date('His'); // Format: YYYYMMDD_HHMMSS
        return "Ekurva {$patientName} {$usiaSebenarnya} {$timeDownload}.pdf";
    }

    private function generateFileNameWhatsapp($patient, $latestAntro)
    {
        // Get first 10 chars of name and replace spaces with underscores
        $patientName = str_replace(' ', '-', substr($patient->nama, 0, 10)); // Limit patient name to 10 chars
        [$tahunUs, $bulanUs, $hariUs] = convertDaysToYear(
            $latestAntro->tgl_periksa,
            $latestAntro->total_usia_hari ?? 0,
        );
        // Replace spaces with underscores in the age description
        $usiaSebenarnya = $tahunUs . '-th-' . $bulanUs . '-bl-' . $hariUs . '-hr';
        $timeDownload = date('His'); // Format: HHMMSS

        // Use hyphen between patient name and age, keep space before timestamp
        return "Ekurva_{$patientName}_{$usiaSebenarnya}_{$timeDownload}.pdf";
    }

    /**
     * Schedule a PDF file for deletion after specified hours
     */
    private function schedulePdfCleanup($filename, $hours = 24)
    {
        try {
            $scheduledTime = now()->addHours($hours);
            Log::info("PDF {$filename} scheduled for deletion at {$scheduledTime}");

            // You'd implement this with a scheduled task in Laravel
            // For now we'll log it - you should implement a proper cleanup mechanism
        } catch (\Exception $e) {
            Log::error("Error scheduling PDF cleanup: " . $e->getMessage());
        }
    }

    /**
     * Normalize phone number format for comparison
     * Converts 08xxx to 628xxx and vice versa
     */
    private function normalizePhoneNumber(string $phoneNumber): string
    {
        // Remove any non-numeric characters
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Convert 08xxx to 628xxx format
        if (substr($phoneNumber, 0, 2) === '08') {
            return '62' . substr($phoneNumber, 1);
        }

        // Convert 628xxx to 08xxx format (though we're not using this now)
        if (substr($phoneNumber, 0, 3) === '628') {
            return '0' . substr($phoneNumber, 2);
        }

        // Return original if no conversion needed
        return $phoneNumber;
    }

    public function generateAndSendCustomPdfSuperAdmin(Request $request, $patientId)
    {
        try {
            // Extract data from request - matching frontend parameters
            $selectedPoints = $request->input('selectedPoints') ?? [];
            $emailAddress = $request->input('emailAddress') ?? null;
            $senderName = $request->input('displayName') ?? $this->senderNameDefault;

            // Log the received data for debugging
            Log::info("Super Admin Custom PDF Request:", [
                'selectedPoints' => $selectedPoints,
                'emailAddress' => $emailAddress,
                'senderName' => $senderName
            ]);

            if (empty($selectedPoints)) {
                Log::error("Tidak ada data yang dipilih untuk download PDF.");
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak ada data point yang dipilih'
                ], 400);
            }

            $penilaianController = new PenilaianController($this->patientService, $this->pointService);
            $result = $penilaianController->prosesPenilaian($selectedPoints, $patientId);

            if (!$result) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak dapat memproses data penilaian'
                ], 400);
            }

            $patient = $result['patient'];
            $latestAntro = $result['latestAntro'];
            $interpretasiGizi = $result['interpretasiGizi'];

            // Collect chart images
            $images = [];
            $kurvaTableSettings = DB::table('kurva_table_settings')->select('judul', 'nama_tabel')->get()->keyBy('nama_tabel')->toArray();

            for ($i = 1; $i <= 12; $i++) {
                $path = public_path("img-public/kurva/chart-{$patientId}-table{$i}.png");

                if (!file_exists($path)) {
                    Log::warning("Gambar chart-{$patientId}-table{$i}.png tidak ditemukan di kurva/");
                    continue;
                }

                $images[] = ("img-public/kurva/chart-{$patientId}-table{$i}.png");
            }

            if (count($images) === 0) {
                Log::error("Tidak ada gambar yang ditemukan untuk PDF.");
                return response()->json(['status' => 'error', 'message' => 'Tidak ada gambar'], 404);
            }

            // Get ads image if available
            $adsImage = null;
            $adsSetting = Cache::rememberForever('pdf_ads', function () {
                return DB::table('lp_settings')->where('key', 'ads')->first();
            });

            if ($adsSetting && !empty($adsSetting->value)) {
                $adsPath = public_path($adsSetting->value);
                if (file_exists($adsPath)) {
                    $adsImage = $adsSetting->value;
                    Log::info("Ads image found and will be included in PDF: {$adsImage}");
                    $images[] = $adsSetting->value;
                }
            }

            // Extract point IDs
            $pointIds = [];
            if (is_array($selectedPoints)) {
                if (isset($selectedPoints['data']) && is_array($selectedPoints['data'])) {
                    foreach ($selectedPoints['data'] as $point) {
                        if (isset($point['id'])) {
                            $pointIds[] = $point['id'];
                        }
                    }
                } else {
                    foreach ($selectedPoints as $point) {
                        if (is_array($point) && isset($point['id'])) {
                            $pointIds[] = $point['id'];
                        } elseif (is_numeric($point)) {
                            $pointIds[] = $point;
                        }
                    }
                }
            } else if (is_numeric($selectedPoints)) {
                $pointIds = [$selectedPoints];
            }

            $antros = DB::table('antro_patients')
                ->whereIn('antro_patients.id', $pointIds)
                ->where(function ($query) {
                    $query->whereNotNull('antro_patients.notes')
                        ->where('antro_patients.notes', '!=', '');
                })
                ->leftJoin('users', 'antro_patients.created_by', '=', 'users.id')
                ->select('antro_patients.tgl_periksa', 'antro_patients.notes', 'users.name as created_by')
                ->orderBy('antro_patients.tgl_periksa', 'desc')
                ->get();

            // Generate PDF
            $pdf = PDF::loadView('pdf.kurva', [
                'images' => $images,
                'patient' => $patient,
                'kurvaTableSettings' => $kurvaTableSettings,
                'logo' => $this->logo,
                'antros' => $antros,
                'latestAntro' => $latestAntro,
                'interpretasiGizi' => $interpretasiGizi,
                'superAdmin' => $this->superAdmin,
                'pdfSetting' => $this->pdfSetting,
            ])
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'enable_remote' => false,
                    'chroot' => public_path('img-public'),
                    'defaultPaperSize' => 'a4',
                    'margin_top' => 8,
                    'margin_right' => 8,
                    'margin_bottom' => 8,
                    'margin_left' => 8,
                ]);

            // Format file name
            $filename = $this->generateFileName($patient, $latestAntro);

            Storage::disk('public')->put("kurva/{$filename}", $pdf->output());

            Log::info("PDF berhasil dibuat dan disimpan di storage/kurva/{$filename}");

            // Determine recipient email - use provided email or fallback to patient/user email
            $recipientEmail = $emailAddress ?? ($patient->email ?? Auth::user()->email);
            $recipientName = $emailAddress ? 'Penerima Custom' : ($patient->email ? $patient->nama : Auth::user()->name);

            if (!$recipientEmail) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email penerima tidak valid'
                ], 400);
            }

            // Dispatch job to send email
            \App\Jobs\SendPdfEmail::dispatch(
                $patient,
                $filename,
                $senderName,
                $recipientEmail,
                $recipientName,
                $images
            );

            return response()->json([
                'status' => 'success',
                'message' => "PDF sedang diproses dan akan dikirim ke email {$recipientEmail}! \nSilahkan cek email di Inbox, Folder Junk Mail atau Folder Spam dalam beberapa menit."
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error saat generate custom PDF Super Admin: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Gagal membuat PDF'], 500);
        }
    }
}
