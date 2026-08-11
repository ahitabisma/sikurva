<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PatientTemplateExport;
use App\Http\Controllers\Controller;
use App\Http\Services\AntroService;
use App\Http\Services\PatientService;
use App\Http\Services\PointService;
use App\Imports\PatientsImport;
use App\Models\KurvaTableSetting;
use Helper\CacheHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class PasienController extends Controller
{
    protected $patientService;
    protected $antroService;
    protected $pointService;
    private $tableColumn;
    private $maxPatientForAdminAwam;
    private $user;

    public function __construct(PatientService $patientService, AntroService $antroService, PointService $pointService)
    {
        $this->user = Auth::user();
        $this->patientService = $patientService;
        $this->antroService = $antroService;
        $this->pointService = $pointService;
        $this->tableColumn = array_merge(KurvaTableSetting::TABLE_COLUMNS, KurvaTableSetting::TABLE_COLUMNS_IG);

        $this->maxPatientForAdminAwam = Cache::rememberForever('max_patient_for_admin_awam', function () {
            return DB::table('user_settings')
                ->where('key', 'max_patients_admin_awam')
                ->value('value');
        });
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $patients = $this->patientService->getPaginated($this->user->id, 25, $search);

        return view("admin.pasien.index")->with([
            'title' => $this->user->is_nakes ? 'Daftar Data Pasien' : 'Daftar Data Anak',
            'patients' => $patients
        ]);
    }

    public function create()
    {
        return view("admin.pasien.create")->with([
            'title' => $this->user->is_nakes ? 'Tambah Daftar Pasien' : 'Tambah Daftar Anak',
        ]);
    }

    public function store(Request $request)
    {
        // Cek apakah user sudah mencapai batas maksimal pasien
        if (!$this->user->is_nakes) {
            $patientCount = $this->patientService->countTotalPatients($this->user->id);

            if ($this->maxPatientForAdminAwam != '' && $this->maxPatientForAdminAwam != null && $patientCount >= $this->maxPatientForAdminAwam) {
                // Jika sudah mencapai batas maksimal, tampilkan pesan error
                return back()->with('error', 'Anda sudah mencapai batas maksimal pasien yang dapat didaftarkan.');
            }
        }

        $context = getInstansiOrUserContext($this->user);

        $pointSetting = $this->pointService->findSettingByName('TAMBAH-PASIEN');

        // 1. Cek dulu apakah total poin cukup (tanpa mengurangi)
        $isEnough = $this->pointService->isPointEnough(
            $context['user_id'],
            $context['instansi_id'],
            $pointSetting->points
        );

        if (!$isEnough) {
            return back()->with('error', 'Poin Anda tidak cukup atau masa aktif poin Anda sudah habis untuk melakukan pendaftaran! Silahkan top up poin terlebih dahulu.');
        }

        // 2. Validasi data dari request
        // Kode Lokal = Kode MR
        $validated = $request->validate([
            'kode_lokal' => 'nullable|string|max:10',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tgl_lahir' => 'required|date',
            'usia_kehamilan_minggu' => 'required|integer|min:27|max:40',
            'tinggi_ayah' => 'nullable|integer',
            'tinggi_ibu' => 'nullable|integer',
            'no_wa' => ['nullable', 'phone:ID', 'max:15'],
            'email' => 'nullable|email|max:255',
        ], [
            'no_wa.phone' => 'Format nomor WA tidak valid.',
            'kode_lokal.unique' => 'Kode MR sudah digunakan.',
            'kode_lokal.max' => 'Kode MR tidak boleh lebih dari 10 karakter.',
            'kode_lokal.string' => 'Kode MR harus berupa string.',
        ]);

        if ($request->no_wa != null) {
            $validated['no_wa'] = '62' . $request->no_wa;
        }

        $validated['created_by'] = $this->user->id;

        // 3. Hitung dan cek kode lokal
        // $nomor_urut = $this->getNextKodeLokal();
        // if ($this->patientService->checkKodeLokal($user, $nomor_urut)) {
        //     return back()->withErrors(['kode_mr' => 'Kode MR sudah digunakan.']);
        // }

        // $validated['kode_lokal'] = $nomor_urut;

        // Count Usia Kehamilan
        if ($validated['usia_kehamilan_minggu'] && $validated['usia_kehamilan_minggu'] >= 37 && $validated['usia_kehamilan_minggu'] <= 40) {
            $validated['count_usia_kehamilan_minggu'] = 40;
        } else {
            $validated['count_usia_kehamilan_minggu'] = $validated['usia_kehamilan_minggu'];
        }

        // 4. Simpan data pasien
        $pasien =  $this->patientService->create($validated);

        // 5. Setelah pasien berhasil ditambahkan, baru kurangi poin
        $desc = $this->user->is_nakes ? 'Pendaftaran Pasien' : 'Pendaftaran Anak';
        $this->pointService->usage(
            $context['user_id'],
            $context['instansi_id'],
            $pointSetting->points,
            $desc,
            $pointSetting->id,
            $pasien->id
        );

        if ($request->has('skip_confirmation') && $request->skip_confirmation) {
            Cookie::queue('skip_confirm', 'true', 60 * 24 * 30); // 30 hari
        }

        $successMsg = $this->user->is_nakes ? 'Pasien berhasil ditambahkan!' : 'Anak berhasil ditambahkan!';
        return redirect()->route('patient.index')
            ->with('success', $successMsg);
    }


    public function edit($id)
    {
        $patient = $this->patientService->findById($id);

        return view("admin.pasien.edit")->with([
            'title' => $this->user->is_nakes ? 'Edit Daftar Pasien' : 'Edit Daftar Anak',
            'patient' => $patient,
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validasi data dari request
        $validated = $request->validate([
            'kode_lokal' => 'nullable|string|max:10',
            'nama' => 'required|string|max:255',
            'tinggi_ayah' => 'nullable|integer',
            'tinggi_ibu' => 'nullable|integer',
            'no_wa' => ['nullable', 'phone:ID', 'max:15'],
            'email' => 'nullable|email|max:255',
        ], [
            'no_wa.phone' => 'Format nomor WA tidak valid.',
            'kode_lokal.unique' => 'Kode MR sudah digunakan.',
            'kode_lokal.max' => 'Kode MR tidak boleh lebih dari 10 karakter.',
            'kode_lokal.string' => 'Kode MR harus berupa string.',
        ]);

        // Tambahkan kode negara 62 ke nomor WA jika ada
        if ($request->filled('no_wa')) {
            $validated['no_wa'] = '62' . $request->no_wa;
        } else {
            $validated['no_wa'] = null; // Jika kosong, set null
        }

        // Update data pasien
        $isUpdated = $this->patientService->update($id, $validated);

        $errorMsg = $this->user->is_nakes ? 'Terjadi kesalahan saat memperbarui pasien!' : 'Terjadi kesalahan saat memperbarui anak!';
        $successMsg = $this->user->is_nakes ? 'Pasien berhasil diperbarui!' : 'Anak berhasil diperbarui!';

        // Cek apakah update berhasil
        if (!$isUpdated) {
            return back()
                ->with('error', $errorMsg);
        }

        return redirect()->route('patient.index')
            ->with('success', $successMsg);
    }

    public function destroy($id)
    {
        $isDeleted = $this->patientService->delete($id);

        $errorMsg = $this->user->is_nakes ? 'Terjadi kesalahan saat menghapus pasien!' : 'Terjadi kesalahan saat menghapus anak!';
        $successMsg = $this->user->is_nakes ? 'Pasien berhasil dihapus!' : 'Anak berhasil dihapus!';

        if (!$isDeleted) {
            return redirect()->route('patient.index')->with('error', $errorMsg);
        }

        return redirect()->route('patient.index')->with('success', $successMsg);
    }

    public function import()
    {
        return view('admin.pasien.import')->with([
            'title' => 'Import Data Pasien'
        ]);
    }

    public function importStore(Request $request)
    {
        // Inisialisasi point service
        $pointSetting = $this->pointService->findSettingByName('IMPORT-PASIEN');

        // Get context for point system
        $context = getInstansiOrUserContext($this->user);

        $isEnough = $this->pointService->isPointEnough(
            $context['user_id'],
            $context['instansi_id'],
            $pointSetting->points
        );

        if (!$isEnough) {
            return redirect()->back()
                ->with('error', 'Poin Anda tidak cukup atau masa aktif poin Anda sudah habis untuk melakukan pendaftaran! Silahkan top up poin terlebih dahulu.');
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        try {
            // Tingkatkan timeout jika memungkinkan
            ini_set('max_execution_time', 300); // 5 menit
            ini_set('memory_limit', '256M'); // Tambah memori limit jika perlu

            // Import dengan menangkap error validasi
            $import = new PatientsImport(Auth::id());
            Excel::import($import, $request->file('file'));

            // Kurangi poin setelah berhasil membuat pasien
            $this->pointService->usage(
                $context['user_id'],
                $context['instansi_id'],
                $pointSetting->points,
                'Pendaftaran Pasien (Import)',
                $pointSetting->id,
                null,
            );

            return redirect()->route('patient.index')
                ->with('success', 'Data pasien berhasil diimport dari Excel');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            // Tangani error validasi dengan lebih detail
            $failures = $e->failures();
            $errorMessages = [];
            $rowsWithErrors = [];

            foreach ($failures as $failure) {
                $row = $failure->row();
                $attribute = $failure->attribute();
                $errors = $failure->errors();

                $rowsWithErrors[$row] = true;

                // Simpan pesan error spesifik untuk tampilan
                foreach ($errors as $error) {
                    $errorMessages[] = "Baris $row: $error";
                }
            }

            // Batasi jumlah pesan error yang ditampilkan
            if (count($errorMessages) > 5) {
                $displayedErrors = array_slice($errorMessages, 0, 5);
                $displayedErrors[] = "...dan " . (count($errorMessages) - 5) . " error lainnya.";
            } else {
                $displayedErrors = $errorMessages;
            }

            $errorMessage = 'Terjadi kesalahan validasi: ' . implode(', ', $displayedErrors);
            $errorMessage .= 'Pastikan file Anda tidak memiliki baris kosong. Coba periksa dan bersihkan file Excel dari baris kosong.';

            Log::error('Validasi import gagal: ' . implode(', ', array_keys($rowsWithErrors)));
            return redirect()->back()->with('error', $errorMessage);
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat mengimport: ' . $e->getMessage());

            // Cek apakah error terkait batas maksimum data
            if (strpos($e->getMessage(), "Maksimal import 50 data") !== false) {
                return redirect()->back()
                    ->with('error', 'Terjadi kesalahan: ' . $e->getMessage() . ' Silakan bagi data menjadi beberapa file yang lebih kecil, maksimal 50 data per file.');
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengimport: ' . $e->getMessage() . '<br><br>Coba bersihkan file Excel dari baris kosong sebelum mengimpor.');
        }
    }

    public function exportTemplate()
    {
        return Excel::download(new PatientTemplateExport, 'template_pasien.xlsx');
    }

    public function preview(string $id)
    {
        // Hapus session agar ketika kembali ke halaman ini tidak ada data yang tersisa
        session()->forget([
            'from_submit',
            'patient',
            'latestAntro',
            'interpretasiGizi',
        ]);

        $patient = $this->patientService->findById($id);

        // Also replace the kurvaTableSettings code:
        $kurvaTableSettings = CacheHelper::getKurvaTableSettings();

        // And update the kurva data loop:
        $kurvaData = collect();
        $jenisKelamin = $patient->jenis_kelamin;

        foreach ($this->tableColumn as $table => $column) {
            $kurvaData[$table] = CacheHelper::getKurvaData($table, $column, $jenisKelamin);
        }

        // Ambil data pasien dari antro (tidak di-cache karena spesifik per pasien)
        $dataAntro = DB::table('antro_patients')
            ->leftJoin('users', 'antro_patients.created_by', '=', 'users.id')
            ->where('antro_patients.patient_id', $patient->id)
            ->select('antro_patients.*', 'users.name as created_by_name')
            ->orderBy('antro_patients.tgl_periksa', 'desc')
            ->get();

        // Process chart data using helper - this moves heavy processing from view to PHP
        $chartData = $this->antroService->processChartData($patient, $dataAntro);
        $superAdmin = CacheHelper::getSuperAdmin();

        return view("admin.pasien.preview")->with([
            'title' => $this->user->is_nakes ? 'Preview Pasien' : 'Preview Anak',
            'patient' => $patient,
            'kurvaTableSettings' => $kurvaTableSettings,
            'kurvaData' => $kurvaData,
            'dataAntro' => $dataAntro,
            'superAdmin' => $superAdmin,
            'chartData' => $chartData,
        ]);
    }

    public function copy(string $patientId)
    {
        $context = getInstansiOrUserContext($this->user);
        $pointSetting = $this->pointService->findSettingByName('COPY');

        $isEnough = $this->pointService->isPointEnough(
            $context['user_id'],
            $context['instansi_id'],
            $pointSetting->points
        );

        if (!$isEnough) {
            return back()->with('error', 'Poin Anda tidak cukup atau masa aktif poin Anda sudah habis untuk melakukan pendaftaran! Silahkan top up poin terlebih dahulu.');
        }

        $patient = $this->patientService->findById($patientId);

        if (!$patient) {
            return redirect()->back()->with('error', 'Pasien tidak ditemukan.');
        }

        if (!$this->user->is_nakes) {
            return redirect()->back()->with('error', 'Anda tidak punya hak akses untuk fitur copy.');
        }

        if ($patient->created_by == $this->user->id) {
            return redirect()->back()->with('error', 'Anda tidak melakukan copy kepada pasien yang Anda buat.');
        }

        // Ubah created by
        $patient->created_by = $this->user->id;
        // Unset
        unset($patient->id, $patient->created_at, $patient->updated_at);

        $copy = $this->patientService->create($patient->toArray());

        if (!$copy) {
            return redirect()->back()->with('error', 'Gagal melakukan copy pasien.');
        }

        // Copy data antro
        $antroData = DB::table('antro_patients')
            ->where('patient_id', $patientId)
            ->get();

        foreach ($antroData as $data) {
            $data->patient_id = $copy->id;
            $data->created_by = $this->user->id;

            unset($data->id, $data->created_at, $data->updated_at);

            $this->antroService->create((array) $data);
        }

        $this->pointService->usage(
            $context['user_id'],
            $context['instansi_id'],
            $pointSetting->points,
            "Copy Pasien",
            $pointSetting->id,
            $copy->id
        );

        return redirect()->route('patient.index')->with('success', 'Berhasil melakukan copy pasien.');
    }
}
