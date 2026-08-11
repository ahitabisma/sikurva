<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Exports\PatientExport;
use App\Exports\PatientTemplateExport;
use App\Http\Controllers\Controller;
use App\Http\Services\AntroService;
use App\Http\Services\PatientService;
use App\Http\Services\PointService;
use App\Imports\PatientsImport;
use App\Models\KurvaTableSetting;
use App\Models\Patient;
use App\Models\User;
use Helper\CacheHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class PasienController extends Controller
{
    protected $patientService;
    protected $antroService;
    protected $pointService;
    private $tableColumn;
    private $user;

    public function __construct(PatientService $patientService, AntroService $antroService, PointService $pointService)
    {
        $this->patientService = $patientService;
        $this->antroService = $antroService;
        $this->pointService = $pointService;
        $this->tableColumn = array_merge(KurvaTableSetting::TABLE_COLUMNS, KurvaTableSetting::TABLE_COLUMNS_IG);
        $this->user = Auth::user();
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $patients = DB::table('patients')
            ->leftJoin('users', 'patients.created_by', '=', 'users.id')
            ->leftJoin('instansis', 'users.instansi_id', '=', 'instansis.id')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('patients.nama', 'LIKE', "%{$search}%")
                        ->orWhere('patients.kode_lokal', 'LIKE', "%{$search}%")
                        ->orWhere('users.name', 'LIKE', "%{$search}%");
                });
            })
            ->select(
                'patients.*',
                'users.name as created_by_name',
            )->orderBy('created_at', 'desc')->paginate(25);

        return view("super-admin.pasien.index")->with([
            'title' => 'Pasien',
            'patients' => $patients
        ]);
    }

    public function create()
    {
        return view("super-admin.pasien.create")->with([
            'title' => 'Tambah Pasien',
        ]);
    }

    public function store(Request $request)
    {
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
        // if ($this->patientService->checkKodeLokal(Auth::user(), $nomor_urut)) {
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
        $this->patientService->create($validated);

        return redirect()->route('super-admin.patient.index')
            ->with('success', 'Data pasien berhasil ditambahkan!');
    }


    public function edit($id)
    {
        $patient = $this->patientService->findById($id);

        return view("super-admin.pasien.edit")->with([
            'title' => 'Edit Pasien',
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

        if (!$isUpdated) {
            return back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data pasien!');
        }

        return redirect()->route('super-admin.patient.index')
            ->with('success', 'Data pasien berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $isDeleted = $this->patientService->delete($id);

        if (!$isDeleted) {
            return redirect()->route('super-admin.patient.index')->with('error', 'Terjadi kesalahan saat menghapus pasien!');
        }

        return redirect()->route('super-admin.patient.index')->with('success', 'Pasien berhasil dihapus!');
    }

    public function import()
    {
        return view('super-admin.pasien.import')->with([
            'title' => 'Import Data Pasien'
        ]);
    }

    public function importStore(Request $request)
    {
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

            return redirect()->route('super-admin.patient.index')
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

        return view("super-admin.pasien.preview")->with([
            'title' => 'Preview Pasien',
            'patient' => $patient,
            'kurvaTableSettings' => $kurvaTableSettings,
            'kurvaData' => $kurvaData,
            'dataAntro' => $dataAntro,
            'superAdmin' => $superAdmin,
            'chartData' => $chartData,
        ]);
    }

    public function export()
    {
        // In your controller method
        return Excel::download(new PatientExport(), 'Data Pasien ' . config('app.name') . '.xlsx');
    }
}
