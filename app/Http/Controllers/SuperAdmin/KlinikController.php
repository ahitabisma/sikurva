<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Services\InstansiService;
use App\Http\Services\PointService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class KlinikController extends Controller
{
    protected $klinikService;
    protected $pointService;

    public function __construct(InstansiService $klinikService, PointService $pointService)
    {
        $this->klinikService = $klinikService;
        $this->pointService = $pointService;
    }

    private function getActivePointsForInstansi($instansiId)
    {
        $query = DB::table('point_batches')
            ->where('instansi_id', $instansiId)
            ->where('expired_at', '>=', now());

        $totalPoin = $query->sum('remaining_points');
        $expiredAt = $query->max('expired_at');

        return [$totalPoin, $expiredAt];
    }


    public function index(Request $request)
    {
        $search = $request->input('search');
        // Setting Download Grafik
        $pointSettingDownload = Cache::remember('point_setting_download', now()->addDays(7), function () {
            return $this->pointService->findSettingByName('DOWNLOAD-GRAFIK');
        });

        $instansiQuery = DB::table('instansis')
            ->leftJoin('users', 'instansis.id', '=', 'users.instansi_id')
            ->leftJoin('patients', 'patients.created_by', '=', 'users.id')
            ->leftJoin('point_transactions as pt_instansi', 'instansis.id', '=', 'pt_instansi.instansi_id')
            ->groupBy(
                'instansis.id',
                'instansis.name',
                'instansis.is_verified',
                'users.name'
            )
            ->select(
                'instansis.id',
                'instansis.name',
                'instansis.is_verified',
                'users.name as user_name',
                DB::raw('COUNT(DISTINCT patients.id) as total_pasien'),
                DB::raw("COUNT(DISTINCT CASE WHEN pt_instansi.point_setting_id = {$pointSettingDownload->id} THEN pt_instansi.id END) as total_download")
            )
            ->when($search, function ($query) use ($search) {
                $query->where('instansis.name', 'LIKE', "%{$search}%");
            })
            ->orderBy('instansis.created_at', 'desc');

        $kliniks = $instansiQuery->paginate(25);

        // Tambahkan poin aktif & status
        foreach ($kliniks as $klinik) {
            [$totalPoin, $expiredAt] = $this->getActivePointsForInstansi($klinik->id);
            $klinik->total_active_points = $totalPoin;
            $klinik->point_status = $expiredAt && $expiredAt >= now() ? 'Aktif' : 'Tidak Aktif';
        }

        return view("super-admin.klinik.index")->with([
            'title' => 'Manajemen Klinik',
            'kliniks' => $kliniks
        ]);
    }

    public function create()
    {
        return view("super-admin.klinik.create")->with([
            'title' => 'Tambah Klinik'
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'verify' => ['required', 'in:1,0'],
            'header_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:1024'],
        ]);

        // Create the klinik first to get the ID
        $klinik = $this->klinikService->createInstansi([
            'name' => $data['name'],
            'is_verified' => $data['verify']
        ]);

        // Handle header image upload if provided
        if ($request->hasFile('header_image')) {
            $file = $request->file('header_image');
            $timestamp = time();
            $ext = $file->getClientOriginalExtension();

            // Set nama file unik untuk klinik
            $uniqueName = 'instansi_header_' . $klinik->id . '_' . $timestamp . '.' . $ext;

            // Semua header disimpan di directory yang sama
            $directory = public_path('img-public/header');

            // Buat folder jika belum ada
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Simpan file baru
            $file->move($directory, $uniqueName);

            // Update klinik with header image
            $klinik->update(['header' => $uniqueName]);
        }

        return redirect()->route('super-admin.klinik.index')->with('success', 'Klinik berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $klinik = $this->klinikService->getInstansiById($id);
        return view("super-admin.klinik.edit")->with([
            'title' => 'Edit Klinik',
            'klinik' => $klinik
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'header_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:1024'],
            'delete_header_image' => ['nullable', 'string'],
            'verify' => ['required', 'in:1,0'],
        ]);

        $klinik = $this->klinikService->getInstansiById($id);

        if (!$klinik) {
            return redirect()->route('super-admin.klinik.index')->with('error', 'Klinik tidak ditemukan');
        }

        // Handle header image deletion
        if ($request->input('delete_header_image') == "1" && $klinik->header) {
            $oldPath = public_path('img-public/header/' . $klinik->header);
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
            $data['header'] = null;
        }
        // Handle header image upload
        elseif ($request->hasFile('header_image')) {
            $file = $request->file('header_image');
            $timestamp = time();
            $ext = $file->getClientOriginalExtension();

            // Set nama file unik untuk klinik
            $uniqueName = 'instansi_header_' . $klinik->id . '_' . $timestamp . '.' . $ext;

            // Semua header disimpan di directory yang sama
            $directory = public_path('img-public/header');

            // Set old header path
            $oldHeader = $klinik->header ?? null;

            // Hapus file lama jika ada
            if ($oldHeader) {
                $oldPath = public_path('img-public/header/' . $oldHeader);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            // Buat folder jika belum ada
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Simpan file baru
            $file->move($directory, $uniqueName);

            // Update data dengan nama file baru
            $data['header'] = $uniqueName;
        }

        // Remove extra field before updating database
        unset($data['delete_header_image']);

        $data['is_verified'] = $data['verify'];
        $klinik->update($data);
        $user = DB::table('users')->where('instansi_id', $klinik->id)->first();

        // Hapus cache header berdasarkan user ID
        if ($user) {
            Cache::forget('header_user_' . $user->id);
        }

        return redirect()->route('super-admin.klinik.index')->with('success', 'Klinik berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $klinik = $this->klinikService->getInstansiById($id);

        if (!$klinik) {
            return redirect()->route('super-admin.klinik.index')->with('error', 'Klinik tidak ditemukan!');
        }

        // Delete the klinik
        $klinik->delete();

        // Delete the associated users
        DB::table('users')->where('instansi_id', $klinik->id)->delete();

        return redirect()->route('super-admin.klinik.index')->with('success', 'Klinik berhasil dihapus!');
    }

    public function verifikasi($id)
    {
        $klinik = $this->klinikService->getInstansiById($id);

        if (!$klinik) {
            return redirect()->route('super-admin.klinik.index')->with('error', 'Klinik tidak ditemukan!');
        }

        // Toggle the verification status
        $klinik->is_verified = !$klinik->is_verified;
        $klinik->save();

        return redirect()->route('super-admin.klinik.index')->with('success', 'Status verifikasi klinik berhasil diperbarui!');
    }
}
