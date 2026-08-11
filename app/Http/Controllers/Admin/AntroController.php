<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AntroTemplateExport;
use App\Http\Controllers\Controller;
use App\Http\Services\AntroService;
use App\Http\Services\PatientService;
use App\Http\Services\PointService;
use App\Imports\AntroImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class AntroController extends Controller
{
    protected $patientService;
    protected $antroService;
    protected $pointService;

    public function __construct(PatientService $patientService, AntroService $antroService, PointService $pointService)
    {
        $this->patientService = $patientService;
        $this->antroService = $antroService;
        $this->pointService = $pointService;
    }

    public function create($patientId)
    {
        $patient = $this->patientService->findById($patientId);
        return view("admin.pasien.antro.create")->with([
            'title' => 'Tambah Antro',
            'patient' => $patient
        ]);
    }

    public function store(Request $request, $patientId)
    {
        // Get context for point system
        $context = getInstansiOrUserContext(Auth::user());
        $pointSetting = $this->pointService->findSettingByName('TAMBAH-ANTRO');

        // Check if user has enough points
        $isEnough = $this->pointService->isPointEnough(
            $context['user_id'],
            $context['instansi_id'],
            $pointSetting->points
        );

        if (!$isEnough) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Poin Anda tidak cukup atau masa aktif poin Anda sudah habis untuk menambahkan data antropometri! Silahkan top up poin terlebih dahulu.');
        }

        // Validasi input
        $validated = $request->validate([
            'tgl_periksa' => 'required|date',
            'berat_badan' => 'nullable|required_if:tinggi_badan,=,null|numeric|min:0|max:175',
            'tinggi_badan' => 'nullable|required_if:berat_badan,=,null|numeric|min:0|max:205',
            'lingkar_kepala' => 'nullable|numeric|min:0|max:56',
            'notes' => Auth::user()->is_nakes ? 'nullable|string' : 'nullable',
            // 'notes.*.content' => Auth::user()->is_nakes ? 'nullable|string' : 'nullable',
        ]);

        // Cari pasien berdasarkan ID
        $patient = $this->patientService->findById($patientId);

        // Hitung usia dalam bulan dan sisa hari
        $tglPeriksa = new \DateTime($validated['tgl_periksa']);
        $tglLahir = new \DateTime($patient->tgl_lahir);

        // Hitung selisih dalam bulan
        $interval = $tglLahir->diff($tglPeriksa);
        $totalMonths = $interval->y * 12 + $interval->m;

        // Pengecekan khusus jika tanggal lahir sama dengan tanggal periksa
        if ($tglLahir == $tglPeriksa) {
            $totalMonths = 0;
            $usiaHari = 0;
            $totalUsiaHari = 0;
        } else {
            // Hitung tanggal terakhir bulan penuh
            $lastFullMonth = (clone $tglLahir)->modify("+{$totalMonths} months");

            // Jika tanggal pemeriksaan sama atau sebelum tanggal lahir di bulan terakhir, kurangi 1 bulan
            if ($tglPeriksa <= $lastFullMonth) {
                $totalMonths -= 1;
                $lastFullMonth = (clone $tglLahir)->modify("+{$totalMonths} months");
            }

            // Hitung sisa hari
            $usiaHari = $tglPeriksa->diff($lastFullMonth)->days;

            // Adjust jika sisa hari >= 30
            if ($usiaHari >= 30) {
                $totalMonths += 1;
                $usiaHari = 0;
            }

            // Hitung total usia dalam hari
            $totalUsiaHari = $tglLahir->diff($tglPeriksa)->days;

            // Pastikan tidak negatif
            $totalMonths = max($totalMonths, 0);
            $usiaHari = max($usiaHari, 0);
            $totalUsiaHari = max($totalUsiaHari, 0);
        }

        // Validasi usia bulan
        if ($totalMonths > 228) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['tgl_periksa' => 'Usia harus antara 0-228 bulan']);
        }

        // Calculate Usia Koreksi dan Usia Gestasi
        // Usia Sebenarnya = Tgl Px - Tgl Lahir
        // Untuk Bayi Cukup bulan 37 - 40 minggu
        if ($patient->usia_kehamilan_minggu && $patient->usia_kehamilan_minggu >= 37 && $patient->usia_kehamilan_minggu <= 40) {
            // Usia Koreksi = Usia Sebenarnya
            if ($usiaHari < 15) {
                $validated['usia_koreksi_bulan'] = $totalMonths;
            } elseif ($usiaHari >= 15 && $usiaHari <= 30) {
                $validated['usia_koreksi_bulan'] = $totalMonths + 1;
            }
            $validated['usia_koreksi_total_hari'] = $totalUsiaHari;
        }
        // Untuk Bayi Kurang bulan <37 minggu
        elseif ($patient->usia_kehamilan_minggu && $patient->usia_kehamilan_minggu < 37) {
            // Usia Sebenarnya = Tgl Px - Tgl Lahir
            // Bila Usia Sebenarnya < 24 bulan
            $us = round($totalUsiaHari / 30.4375);
            if ($us < 24) {
                // UK = Tgl Periksa – (Tgl lahir + ((40*7)-(US*7))) Satuan hr (bila negative --> hasilnya ‘0”)
                $uk = $totalUsiaHari - ((40 * 7) - ($patient->count_usia_kehamilan_minggu * 7));
                $uk = max(0, $uk);
                $validated['usia_koreksi_bulan'] = round($uk / 30.44);
                $validated['usia_koreksi_total_hari'] = $uk;

                // UG = xxx Hari (24 – 64 mgg / 168 – 448 hr)
                $ug = ($patient->count_usia_kehamilan_minggu * 7) + $totalUsiaHari;
                $ug = max(0, $ug);

                // Jika lebih dari 448 hari, tidak dinilai lagi
                $validated['usia_gestasi_minggu'] = round($ug / 7);
                $validated['usia_gestasi_total_hari'] = $ug;
            } else {
                // Bila > 24 bln
                // UK = US
                $validated['usia_koreksi_bulan'] = $us;
                $validated['usia_koreksi_total_hari'] = $totalUsiaHari;
            }
        }

        // Validasi berdasarkan range usia
        $errors = [];
        if ($totalMonths <= 60) {
            // Validation for premature babies with PMA < 64 weeks
            if ($patient->usia_kehamilan_minggu < 37 && isset($validated['usia_gestasi_minggu']) && $validated['usia_gestasi_minggu'] < 64) {
                if (isset($validated['berat_badan']) && $validated['berat_badan'] < 0.40 || isset($validated['berat_badan']) && $validated['berat_badan'] > 12.00) {
                    $errors['berat_badan'] = 'Berat badan harus antara 0,40 - 12,00 kg untuk usia PMA < 64 minggu';
                }
                if (isset($validated['tinggi_badan']) && $validated['tinggi_badan'] < 26.0 || isset($validated['tinggi_badan']) && $validated['tinggi_badan'] > 76.0) {
                    $errors['tinggi_badan'] = 'Tinggi badan harus antara 26,5 - 76,0 cm untuk usia PMA < 64 minggu';
                }
                if (isset($validated['lingkar_kepala']) && ($validated['lingkar_kepala'] < 19.0 || $validated['lingkar_kepala'] > 48.0)) {
                    $errors['lingkar_kepala'] = 'Lingkar kepala harus antara 19,0 - 48,0 cm untuk usia PMA < 64 minggu';
                }
            } else {
                if (isset($validated['berat_badan']) && $validated['berat_badan'] < 1.70 || isset($validated['berat_badan']) && $validated['berat_badan'] > 30.00) {
                    $errors['berat_badan'] = 'Berat badan harus antara 1,70 - 30,00 kg untuk usia <= 60 bulan';
                }
                if (isset($validated['tinggi_badan']) && $validated['tinggi_badan'] < 42.5 || isset($validated['tinggi_badan']) && $validated['tinggi_badan'] > 125.0) {
                    $errors['tinggi_badan'] = 'Tinggi badan harus antara 42,5 - 125,0 cm untuk usia <= 60 bulan';
                }
                if (isset($validated['lingkar_kepala']) && ($validated['lingkar_kepala'] < 30.0 || $validated['lingkar_kepala'] > 56.0)) {
                    $errors['lingkar_kepala'] = 'Lingkar kepala harus antara 30,0 - 56,0 cm untuk usia <= 60 bulan';
                }
            }
        } elseif ($totalMonths >= 61 && $totalMonths <= 120) {
            if (isset($validated['berat_badan']) && $validated['berat_badan'] < 11.50 || isset($validated['berat_badan']) && $validated['berat_badan'] > 67.50) {
                $errors['berat_badan'] = 'Berat badan harus antara 11,50 - 67,50 kg untuk usia 61-120 bulan';
            }
            if (isset($validated['tinggi_badan']) && $validated['tinggi_badan'] < 92.5 || isset($validated['tinggi_badan']) && $validated['tinggi_badan'] > 205.0) {
                $errors['tinggi_badan'] = 'Tinggi badan harus antara 92,5 - 205,0 cm untuk usia 61-228 bulan';
            }
            if (isset($validated['lingkar_kepala']) && $validated['lingkar_kepala'] != 0) {
                $errors['lingkar_kepala'] = 'Lingkar kepala tidak dinilai untuk usia > 60 bulan';
                $validated['lingkar_kepala'] = null;
            }
        } elseif ($totalMonths >= 121 && $totalMonths <= 228) {
            if (isset($validated['berat_badan']) && $validated['berat_badan'] < 18.50 || isset($validated['berat_badan']) && $validated['berat_badan'] > 175.00) {
                $errors['berat_badan'] = 'Berat badan harus antara 18,50 - 175,00 kg untuk usia 121-228 bulan';
            }
            if (isset($validated['tinggi_badan']) && $validated['tinggi_badan'] < 92.5 || isset($validated['tinggi_badan']) && $validated['tinggi_badan'] > 205.0) {
                $errors['tinggi_badan'] = 'Tinggi badan harus antara 92,5 - 205,0 cm untuk usia 61-228 bulan';
            }
            if (isset($validated['lingkar_kepala']) && $validated['lingkar_kepala'] != 0) {
                $errors['lingkar_kepala'] = 'Lingkar kepala tidak dinilai untuk usia > 60 bulan';
                $validated['lingkar_kepala'] = null;
            }
        }

        if (!empty($errors)) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($errors);
        }

        // Buat data antropometri baru
        $antro = $this->antroService->create([
            'created_by' => Auth::id(),
            'patient_id' => $patientId,
            'tgl_periksa' => $validated['tgl_periksa'],
            'usia_bulan' => $totalMonths,
            'usia_hari' => $usiaHari,
            'total_usia_hari' => $totalUsiaHari,
            'usia_koreksi_bulan' => $validated['usia_koreksi_bulan'] ?? null,
            'usia_koreksi_total_hari' => $validated['usia_koreksi_total_hari'] ?? null,
            'usia_gestasi_minggu' => $validated['usia_gestasi_minggu'] ?? null,
            'usia_gestasi_total_hari' => $validated['usia_gestasi_total_hari'] ?? null,
            'berat_badan' => (isset($validated['berat_badan']) && $validated['berat_badan'] == 0 ? null : ($validated['berat_badan']) ?? null),
            'tinggi_badan' => (isset($validated['tinggi_badan']) && $validated['tinggi_badan'] == 0 ? null : ($validated['tinggi_badan']) ?? null),
            'lingkar_kepala' => (isset($validated['lingkar_kepala']) && $validated['lingkar_kepala'] == 0 ? null : ($validated['lingkar_kepala']) ?? null),
            'notes' => $validated['notes'] ?? null,
        ]);

        // Setelah berhasil menambahkan data, kurangi poin
        if ($antro) {
            $this->pointService->usage(
                $context['user_id'],
                $context['instansi_id'],
                $pointSetting->points,
                'Tambah Data Antropometri',
                $pointSetting->id,
                $patientId
            );

            // Log penggunaan poin
            Log::info("Poin berhasil digunakan untuk menambah data antropometri ID: {$patientId}", [
                'user_id' => $context['user_id'],
                'instansi_id' => $context['instansi_id'],
                'points' => $pointSetting->points,
            ]);
        }

        if ($request->has('skip_confirmation') && $request->skip_confirmation) {
            Cookie::queue('skip_confirm', 'true', 60 * 24 * 30); // 30 hari
        }

        // Redirect dengan pesan sukses
        return redirect()
            ->route('patient.preview', ['id' => $patient->id])
            ->with('success', 'Data antropometri berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $antro = $this->antroService->getById($id);
        $patient = $this->patientService->findById($antro->patient_id);

        // Parse notes from JSON if they exist
        if ($antro->notes) {
            try {
                $parsedNotes = json_decode($antro->notes, true);
                // If it's valid JSON, use it
                if (json_last_error() === JSON_ERROR_NONE && is_array($parsedNotes)) {
                    $antro->parsedNotes = $parsedNotes;
                } else {
                    // If it's not valid JSON (old format), create a compatible structure
                    $antro->parsedNotes = [['content' => $antro->notes]];
                }
            } catch (\Exception $e) {
                // Fallback if there's an error
                $antro->parsedNotes = [['content' => $antro->notes]];
            }
        } else {
            $antro->parsedNotes = [['content' => '']];
        }

        return view("admin.pasien.antro.edit")->with([
            'title' => 'Edit Antro',
            'patient' => $patient,
            'antro' => $antro
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'tgl_periksa' => 'required|date',
            'berat_badan' => 'nullable|required_if:tinggi_badan,=,null|numeric|min:0|max:175',
            'tinggi_badan' => 'nullable|required_if:berat_badan,=,null|numeric|min:0|max:205',
            'lingkar_kepala' => 'nullable|numeric|min:0|max:56',
            'notes' => Auth::user()->is_nakes ? 'nullable|string' : 'nullable',
            // 'notes.*.content' => Auth::user()->is_nakes ? 'nullable|string' : 'nullable',
        ]);

        // Cari antro berdasarkan ID
        $antro = $this->antroService->getById($id);
        $patient = $this->patientService->findById($antro->patient_id);

        // Hitung usia dalam bulan dan sisa hari
        $tglPeriksa = new \DateTime($validated['tgl_periksa']);
        $tglLahir = new \DateTime($patient->tgl_lahir);

        // Hitung selisih dalam bulan
        $interval = $tglLahir->diff($tglPeriksa);
        $totalMonths = $interval->y * 12 + $interval->m;

        // Pengecekan khusus jika tanggal lahir sama dengan tanggal periksa
        if ($tglLahir == $tglPeriksa) {
            $totalMonths = 0;
            $usiaHari = 0;
            $totalUsiaHari = 0;
        } else {
            // Hitung tanggal terakhir bulan penuh
            $lastFullMonth = (clone $tglLahir)->modify("+{$totalMonths} months");

            // Jika tanggal pemeriksaan sama atau sebelum tanggal lahir di bulan terakhir, kurangi 1 bulan
            if ($tglPeriksa <= $lastFullMonth) {
                $totalMonths -= 1;
                $lastFullMonth = (clone $tglLahir)->modify("+{$totalMonths} months");
            }

            // Hitung sisa hari
            $usiaHari = $tglPeriksa->diff($lastFullMonth)->days;

            // Adjust jika sisa hari >= 30
            if ($usiaHari >= 30) {
                $totalMonths += 1;
                $usiaHari = 0;
            }

            // Hitung total usia dalam hari
            $totalUsiaHari = $tglLahir->diff($tglPeriksa)->days;

            // Pastikan tidak negatif
            $totalMonths = max($totalMonths, 0);
            $usiaHari = max($usiaHari, 0);
            $totalUsiaHari = max($totalUsiaHari, 0);
        }

        // Validasi usia bulan
        if ($totalMonths > 228) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['tgl_periksa' => 'Usia harus antara 0-228 bulan']);
        }

        // Calculate Usia Koreksi dan Usia Gestasi
        // Usia Sebenarnya = Tgl Px - Tgl Lahir
        // Untuk Bayi Cukup bulan 37 - 40 minggu
        if ($patient->usia_kehamilan_minggu && $patient->usia_kehamilan_minggu >= 37 && $patient->usia_kehamilan_minggu <= 40) {
            // Usia Koreksi = Usia Sebenarnya
            if ($usiaHari < 15) {
                $validated['usia_koreksi_bulan'] = $totalMonths;
            } elseif ($usiaHari >= 15 && $usiaHari <= 30) {
                $validated['usia_koreksi_bulan'] = $totalMonths + 1;
            }
            $validated['usia_koreksi_total_hari'] = $totalUsiaHari;
        }
        // Untuk Bayi Kurang bulan <37 minggu
        elseif ($patient->usia_kehamilan_minggu && $patient->usia_kehamilan_minggu < 37) {
            // Usia Sebenarnya = Tgl Px - Tgl Lahir
            // Bila Usia Sebenarnya < 24 bulan
            $us = round($totalUsiaHari / 30.4375);
            if ($us < 24) {
                // UK = Tgl Periksa – (Tgl lahir + ((40*7)-(US*7))) Satuan hr (bila negative --> hasilnya ‘0”)
                $uk = $totalUsiaHari - ((40 * 7) - ($patient->count_usia_kehamilan_minggu * 7));
                $uk = max(0, $uk);
                $validated['usia_koreksi_bulan'] = round($uk / 30.44);
                $validated['usia_koreksi_total_hari'] = $uk;

                // UG = xxx Hari (24 – 64 mgg / 168 – 448 hr)
                $ug = ($patient->count_usia_kehamilan_minggu * 7) + $totalUsiaHari;
                $ug = max(0, $ug);

                // Jika lebih dari 448 hari, tidak dinilai lagi
                $validated['usia_gestasi_minggu'] = round($ug / 7);
                $validated['usia_gestasi_total_hari'] = $ug;
            } else {
                // Bila > 24 bln
                // UK = US
                $validated['usia_koreksi_bulan'] = $us;
                $validated['usia_koreksi_total_hari'] = $totalUsiaHari;
            }
        }

        // Validasi berdasarkan range usia
        $errors = [];
        if ($totalMonths <= 60) {
            // Validation for premature babies with PMA < 64 weeks
            if ($patient->usia_kehamilan_minggu < 37 && isset($validated['usia_gestasi_minggu']) && $validated['usia_gestasi_minggu'] < 64) {
                if (isset($validated['berat_badan']) && $validated['berat_badan'] < 0.40 || isset($validated['berat_badan']) && $validated['berat_badan'] > 12.00) {
                    $errors['berat_badan'] = 'Berat badan harus antara 0,40 - 12,00 kg untuk usia PMA < 64 minggu';
                }
                if (isset($validated['tinggi_badan']) && $validated['tinggi_badan'] < 26.0 || isset($validated['tinggi_badan']) && $validated['tinggi_badan'] > 76.0) {
                    $errors['tinggi_badan'] = 'Tinggi badan harus antara 26,5 - 76,0 cm untuk usia PMA < 64 minggu';
                }
                if (isset($validated['lingkar_kepala']) && ($validated['lingkar_kepala'] < 19.0 || $validated['lingkar_kepala'] > 48.0)) {
                    $errors['lingkar_kepala'] = 'Lingkar kepala harus antara 19,0 - 48,0 cm untuk usia PMA < 64 minggu';
                }
            } else {
                if (isset($validated['berat_badan']) && $validated['berat_badan'] < 1.70 || isset($validated['berat_badan']) && $validated['berat_badan'] > 30.00) {
                    $errors['berat_badan'] = 'Berat badan harus antara 1,70 - 30,00 kg untuk usia <= 60 bulan';
                }
                if (isset($validated['tinggi_badan']) && $validated['tinggi_badan'] < 42.5 || isset($validated['tinggi_badan']) && $validated['tinggi_badan'] > 125.0) {
                    $errors['tinggi_badan'] = 'Tinggi badan harus antara 42,5 - 125,0 cm untuk usia <= 60 bulan';
                }
                if (isset($validated['lingkar_kepala']) && ($validated['lingkar_kepala'] < 30.0 || $validated['lingkar_kepala'] > 56.0)) {
                    $errors['lingkar_kepala'] = 'Lingkar kepala harus antara 30,0 - 56,0 cm untuk usia <= 60 bulan';
                }
            }
        } elseif ($totalMonths >= 61 && $totalMonths <= 120) {
            if (isset($validated['berat_badan']) && $validated['berat_badan'] < 11.50 || isset($validated['berat_badan']) && $validated['berat_badan'] > 67.50) {
                $errors['berat_badan'] = 'Berat badan harus antara 11,50 - 67,50 kg untuk usia 61-120 bulan';
            }
            if (isset($validated['tinggi_badan']) && $validated['tinggi_badan'] < 92.5 || isset($validated['tinggi_badan']) && $validated['tinggi_badan'] > 205.0) {
                $errors['tinggi_badan'] = 'Tinggi badan harus antara 92,5 - 205,0 cm untuk usia 61-228 bulan';
            }
            if (isset($validated['lingkar_kepala']) && $validated['lingkar_kepala'] != 0) {
                $errors['lingkar_kepala'] = 'Lingkar kepala tidak dinilai untuk usia > 60 bulan';
                $validated['lingkar_kepala'] = null;
            }
        } elseif ($totalMonths >= 121 && $totalMonths <= 228) {
            if (isset($validated['berat_badan']) && $validated['berat_badan'] < 18.50 || isset($validated['berat_badan']) && $validated['berat_badan'] > 175.00) {
                $errors['berat_badan'] = 'Berat badan harus antara 18,50 - 175,00 kg untuk usia 121-228 bulan';
            }
            if (isset($validated['tinggi_badan']) && $validated['tinggi_badan'] < 92.5 || isset($validated['tinggi_badan']) && $validated['tinggi_badan'] > 205.0) {
                $errors['tinggi_badan'] = 'Tinggi badan harus antara 92,5 - 205,0 cm untuk usia 61-228 bulan';
            }
            if (isset($validated['lingkar_kepala']) && $validated['lingkar_kepala'] != 0) {
                $errors['lingkar_kepala'] = 'Lingkar kepala tidak dinilai untuk usia > 60 bulan';
                $validated['lingkar_kepala'] = null;
            }
        }

        // Jika ada error, kembalikan dengan pesan
        if (!empty($errors)) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($errors);
        }

        // Perbarui data antropometri
        $isUpdated = $this->antroService->update($id, [
            'patient_id' => $patient->id,
            'tgl_periksa' => $validated['tgl_periksa'],
            'usia_bulan' => $totalMonths,
            'usia_hari' => $usiaHari,
            'total_usia_hari' => $totalUsiaHari,
            'usia_koreksi_bulan' => $validated['usia_koreksi_bulan'] ?? null,
            'usia_koreksi_total_hari' => $validated['usia_koreksi_total_hari'] ?? null,
            'usia_gestasi_minggu' => $validated['usia_gestasi_minggu'] ?? null,
            'usia_gestasi_total_hari' => $validated['usia_gestasi_total_hari'] ?? null,
            'berat_badan' => (isset($validated['berat_badan']) && $validated['berat_badan'] == 0 ? null : ($validated['berat_badan']) ?? null),
            'tinggi_badan' => (isset($validated['tinggi_badan']) && $validated['tinggi_badan'] == 0 ? null : ($validated['tinggi_badan']) ?? null),
            'lingkar_kepala' => (isset($validated['lingkar_kepala']) && $validated['lingkar_kepala'] == 0 ? null : ($validated['lingkar_kepala']) ?? null),
            'notes' => $validated['notes'] ?? null,
        ]);

        if (!$isUpdated) {
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data antro.');
        }

        // Redirect dengan pesan sukses
        return redirect()
            ->route('patient.preview', ['id' => $patient->id])
            ->with('success', 'Data antropometri berhasil diperbarui.');
    }

    public function updateNotes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'notes' => 'nullable|string',
            'antro_id' => 'required|exists:antro_patients,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $antro = $this->antroService->getById($request->antro_id);
            $antro->notes = $request->notes;
            $antro->save();

            return response()->json([
                'success' => 'Catatan berhasil disimpan!',
                'reload' => false // set to true if you want to reload the page
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => ['general' => ['Terjadi kesalahan saat menyimpan catatan.']]
            ], 500);
        }
    }

    public function destroy($id)
    {
        $isDeleted = $this->antroService->delete($id);
        if (!$isDeleted) {
            return back()->with('error', 'Data antropometri gagal dihapus.');
        }
        return back()->with('success', 'Data antropometri berhasil dihapus.');
    }

    public function import($patientId)
    {
        $patient = $this->patientService->findById($patientId);
        return view('admin.pasien.antro.import')->with([
            'title' => 'Import Data Antro ' . $patient->nama,
            'patient' => $patient
        ]);
    }

    public function importStore(Request $request, $patientId)
    {
        // Inisialisasi point service
        $pointSetting = $this->pointService->findSettingByName('IMPORT-ANTRO');

        // Get context for point system
        $user = Auth::user();
        $context = getInstansiOrUserContext($user);

        $isEnough = $this->pointService->isPointEnough(
            $context['user_id'],
            $context['instansi_id'],
            $pointSetting->points
        );

        if (!$isEnough) {
            return redirect()->back()->with('Poin Anda tidak cukup atau masa aktif poin Anda sudah habis untuk import data antro! Silahkan top up poin terlebih dahulu.');
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        try {
            // Tingkatkan timeout jika memungkinkan
            ini_set('max_execution_time', 300); // 5 menit
            ini_set('memory_limit', '256M'); // Tambah memori limit jika perlu

            // Import dengan menangkap error validasi
            $import = new AntroImport($patientId);
            Excel::import($import, $request->file('file'));

            // Kurangi poin setelah berhasil membuat pasien
            $this->pointService->usage(
                $context['user_id'],
                $context['instansi_id'],
                $pointSetting->points,
                'Import Data Antropometri',
                $pointSetting->id,
                null,
            );

            return redirect()->route('patient.preview', ['id' => $patientId])
                ->with('success', 'Data antro berhasil diimport dari Excel');
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
            if (strpos($e->getMessage(), "Maksimal import 25 data") !== false) {
                return redirect()->back()
                    ->with('error', 'Terjadi kesalahan: ' . $e->getMessage() . ' Silakan bagi data menjadi beberapa file yang lebih kecil, maksimal 25 data per file.');
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengimport: ' . $e->getMessage() . ' Coba bersihkan file Excel dari baris kosong sebelum mengimpor.');
        }
    }

    public function exportTemplate()
    {
        return Excel::download(new AntroTemplateExport, 'template_antro.xlsx');
    }
}
