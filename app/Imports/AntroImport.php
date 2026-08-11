<?php

namespace App\Imports;

use App\Http\Services\PointService;
use App\Models\AntroPatient;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithValidation;

class AntroImport implements ToModel, WithHeadingRow, WithValidation, WithProgressBar
{
    use Importable;

    private $patientId;
    private $heading = 15; // Default seperti sebelumnya
    private $rowCount = 0; // Menambahkan counter untuk jumlah baris
    private $maxRows = 25; // Maksimal 25 data yang diperbolehkan
    private $currentRowNumber = 0; // Menyimpan nomor baris saat ini

    /**
     * Constructor with patient ID and point service
     *
     * @param int $patientId
     * @param PointService $pointService
     * @param array $context
     * @param object $pointSetting
     */
    public function __construct($patientId)
    {
        $this->patientId = $patientId;
    }

    private function parseExcelDate($date)
    {
        if (empty($date)) {
            return null;
        }

        try {
            // Log the incoming date for debugging
            // Log::info("Parsing date: " . $date);

            // Check if the date is a numeric Excel date serial number
            if (is_numeric($date)) {
                // Convert Excel date serial number to Carbon date
                // Excel dates start from January 0, 1900 (though 1900 is not a leap year)
                // So we need to add 1900 years to the number of days, and subtract 2
                // (adjustment for Excel's leap year bug for 1900)
                $carbonDate = Carbon::createFromDate(1899, 12, 30)->addDays((int)$date);
                return $carbonDate->format('Y-m-d');
            }

            // Handle string date formats
            $date = trim($date);
            $parts = preg_split('/[-\/]/', $date); // support "15-05-99", "15/05/99", "15-05-1999", or "15/05/1999"

            if (count($parts) !== 3) {
                throw new \Exception('Format tanggal tidak valid. Harus DD-MM-YY atau DD-MM-YYYY.');
            }

            [$day, $month, $year] = array_map('intval', $parts);

            if ($day < 1 || $day > 31 || $month < 1 || $month > 12) {
                throw new \Exception('Komponen tanggal tidak valid: Hari atau bulan di luar jangkauan.');
            }

            // Penanganan tahun 2-digit atau 4-digit
            if ($year < 100) {
                // Konversi dua digit tahun
                $fullYear = $year >= 50 ? 1900 + $year : 2000 + $year;
            } else {
                // Gunakan tahun empat digit yang sudah ada
                $fullYear = $year;
            }

            return Carbon::create($fullYear, $month, $day)->format('Y-m-d');
        } catch (\Exception $e) {
            Log::error('Gagal memproses tanggal lahir: ' . $date . ' - ' . $e->getMessage());
            throw new \Exception('Tanggal lahir "' . $date . '" tidak valid. Gunakan format DD-MM-YY atau DD-MM-YYYY.');
        }
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            // Log::info('Data baris dari Excel Antro:', $row);
            $this->currentRowNumber = $this->rowCount + $this->heading + 1;

            if (Auth::user()->hasRole('admin')) {
                // Cek apakah sudah melebihi batas maksimal 25 baris
                if (++$this->rowCount > $this->maxRows) {
                    throw new \Exception("Maksimal import 25 data antro. Data ke-" . $this->rowCount . " dan seterusnya tidak akan diproses.");
                }
            }

            // Cek baris kosong LEBIH KETAT - periksa kolom kunci untuk tgl_periksa
            // Juga cek apakah berat_badan dan tinggi_badan keduanya kosong
            if (
                empty($row['tgl_periksa'] ?? '') &&
                empty($row['berat_badan'] ?? '') &&
                empty($row['tinggi_badan'] ?? '') &&
                empty($row['lingkar_kepala'] ?? '')
            ) {
                $this->rowCount--; // Kurangi lagi counternya jika ini baris kosong
                return null; // Skip baris kosong tanpa melempar error
            }

            // Normalisasi key array untuk menangani variasi format
            $row = array_change_key_case($row, CASE_LOWER);

            // Pastikan semua key yang diperlukan ada
            $requiredKeys = ['tgl_periksa'];
            foreach ($requiredKeys as $key) {
                if (!array_key_exists($key, $row) || empty($row[$key])) {
                    throw new \Exception("Kolom '$key' tidak ditemukan atau kosong di file Excel Antro.");
                }
            }

            // Validasi minimal satu dari berat_badan atau tinggi_badan harus diisi
            if ((empty($row['berat_badan'] ?? '') || $row['berat_badan'] == 0) &&
                (empty($row['tinggi_badan'] ?? '') || $row['tinggi_badan'] == 0)
            ) {
                throw new \Exception("Minimal salah satu dari kolom 'berat_badan' atau 'tinggi_badan' harus diisi. (Baris " . $this->currentRowNumber . ")");
            }

            // Lewati baris yang merupakan keterangan
            if (isset($row['tgl_periksa']) && (
                str_contains($row['tgl_periksa'], 'Keterangan Pengisian') ||
                str_starts_with($row['tgl_periksa'], '•')
            )) {
                $this->rowCount--; // Kurangi counter jika ini baris keterangan
                return null; // Skip baris ini
            }

            // Cari pasien berdasarkan patient_id
            $patient = DB::table('patients')->where('id', $this->patientId)->first();
            if (!$patient) {
                throw new \Exception("Pasien dengan ID {$this->patientId} tidak ditemukan.");
            }

            // Hitung usia dalam bulan dan sisa hari
            $formatTglPeriksa = $this->parseExcelDate($row['tgl_periksa']);
            $tglPeriksa = new \DateTime($formatTglPeriksa);
            $tglLahir = new \DateTime($patient->tgl_lahir);

            // Tambahan: Validasi tgl periksa tidak boleh kurang dari tgl lahir
            if ($tglPeriksa < $tglLahir) {
                throw new \Exception("Tanggal periksa tidak boleh kurang dari tanggal lahir pasien (ID: {$this->patientId}). (Baris " . $this->currentRowNumber . ")");
            }

            // Hitung selisih dalam bulan
            $interval = $tglLahir->diff($tglPeriksa);
            $totalMonths = $interval->y * 12 + $interval->m;

            // Hitung tanggal terakhir bulan penuh
            $lastFullMonth = (clone $tglLahir)->modify("+{$totalMonths} months");

            // Tentukan sisa hari
            $usiaHari = 0;
            if ($tglPeriksa->format('d') == $tglLahir->format('d')) {
                $usiaHari = 0; // Hari sama, sisa hari = 0
            } elseif ($tglPeriksa <= $lastFullMonth) {
                $totalMonths -= 1;
                $lastFullMonth = (clone $tglLahir)->modify("+{$totalMonths} months");
                $usiaHari = $tglPeriksa->diff($lastFullMonth)->days;
            } else {
                $usiaHari = $tglPeriksa->diff($lastFullMonth)->days;
            }

            // Hitung total usia dalam hari
            $totalUsiaHari = $tglLahir->diff($tglPeriksa)->days;

            // Pastikan tidak negatif
            $totalMonths = max($totalMonths, 0);
            $usiaHari = max($usiaHari, 0);
            $totalUsiaHari = max($totalUsiaHari, 0);

            // --- Tambahan: Usia Koreksi & Usia Gestasi ---
            $usia_koreksi_bulan = null;
            $usia_koreksi_total_hari = null;
            $usia_gestasi_minggu = null;
            $usia_gestasi_total_hari = null;

            if (isset($patient->usia_kehamilan_minggu)) {
                // Bayi cukup bulan (37-40 minggu)
                if ($patient->usia_kehamilan_minggu >= 37 && $patient->usia_kehamilan_minggu <= 40) {
                    if ($usiaHari < 15) {
                        $usia_koreksi_bulan = $totalMonths;
                    } elseif ($usiaHari >= 15 && $usiaHari <= 30) {
                        $usia_koreksi_bulan = $totalMonths + 1;
                    }
                    $usia_koreksi_total_hari = $totalUsiaHari;
                }
                // Bayi kurang bulan (<37 minggu)
                elseif ($patient->usia_kehamilan_minggu < 37) {
                    $us = round($totalUsiaHari / 30.4375);
                    $count_usia_kehamilan_minggu = $patient->count_usia_kehamilan_minggu ?? $patient->usia_kehamilan_minggu;
                    if ($us < 24) {
                        $ug = ($count_usia_kehamilan_minggu * 7) + $totalUsiaHari;
                        $ug = max(0, $ug);

                        $usia_gestasi_minggu = round($ug / 7);
                        $usia_gestasi_total_hari = $ug;

                        $uk = $totalUsiaHari - ((40 * 7) - ($count_usia_kehamilan_minggu * 7));
                        $uk = max(0, $uk);
                        $usia_koreksi_bulan = round($uk / 30.44);
                        $usia_koreksi_total_hari = $uk;
                    } else {
                        $usia_koreksi_total_hari = $totalUsiaHari;
                        $usia_koreksi_bulan = $us;
                    }
                }
            }

            // Validasi usia
            if ($totalMonths > 228) {
                throw new \Exception("Usia untuk pasien {$patient->nama} harus antara 0-228 bulan.");
            }

            // Validasi range berdasarkan usia
            if ($totalMonths <= 60) {
                if ($patient->usia_kehamilan_minggu < 37 && $usia_gestasi_minggu && $usia_gestasi_minggu < 64) {
                    if (isset($row['berat_badan']) && $row['berat_badan'] < 0.40 || isset($row['berat_badan']) && $row['berat_badan'] > 12.00) {
                        throw new \Exception("Berat badan untuk pasien {$patient->nama} harus antara 0,40 - 12,00 kg (usia PMA kurang dari 64 minggu). (Baris " . $this->currentRowNumber . ")");
                    }
                    if (isset($row['tinggi_badan']) && $row['tinggi_badan'] < 26.0 || isset($row['tinggi_badan']) && $row['tinggi_badan'] > 76.0) {
                        throw new \Exception("Tinggi badan untuk pasien {$patient->nama} harus antara 26,0 - 76,0 cm (usia PMA kurang dari 64 minggu). (Baris " . $this->currentRowNumber . ")");
                    }
                    if (isset($row['lingkar_kepala']) && $row['lingkar_kepala'] && $row['lingkar_kepala'] < 19.0 || isset($row['lingkar_kepala']) && $row['lingkar_kepala'] && $row['lingkar_kepala'] > 48.0) {
                        throw new \Exception("Lingkar kepala untuk pasien {$patient->nama} harus antara 19,0 - 48,0 cm (usia PMA kurang dari 64 minggu). (Baris " . $this->currentRowNumber . ")");
                    }
                } else {
                    if (isset($row['berat_badan']) && $row['berat_badan'] < 1.70 || isset($row['berat_badan']) && $row['berat_badan'] > 30.00) {
                        throw new \Exception("Berat badan untuk pasien {$patient->nama} harus antara 1,70 - 30,00 kg (usia kurang dari 60 bulan). (Baris " . $this->currentRowNumber . ")");
                    }
                    if (isset($row['tinggi_badan']) && $row['tinggi_badan'] < 42.5 || isset($row['tinggi_badan']) && $row['tinggi_badan'] > 125.0) {
                        throw new \Exception("Tinggi badan untuk pasien {$patient->nama} harus antara 42,5 - 125,0 cm (usia kurang dari 60 bulan). (Baris " . $this->currentRowNumber . ")");
                    }
                    if (isset($row['lingkar_kepala']) && $row['lingkar_kepala'] && $row['lingkar_kepala'] < 30.0 || isset($row['lingkar_kepala']) && $row['lingkar_kepala'] && $row['lingkar_kepala'] > 56.0) {
                        throw new \Exception("Lingkar kepala untuk pasien {$patient->nama} harus antara 30,0 - 56,0 cm (usia kurang dari 60 bulan). (Baris " . $this->currentRowNumber . ")");
                    }
                }
            } elseif ($totalMonths >= 61 && $totalMonths <= 120) {
                if (isset($row['berat_badan']) && $row['berat_badan'] < 11.50 || isset($row['berat_badan']) && $row['berat_badan'] > 67.50) {
                    throw new \Exception("Berat badan untuk pasien {$patient->nama} harus antara 11,50 - 67,50 kg (usia 61-120 bulan). (Baris " . $this->currentRowNumber . ")");
                }
                if (isset($row['tinggi_badan']) && $row['tinggi_badan'] < 92.5 || isset($row['tinggi_badan']) && $row['tinggi_badan'] > 205.0) {
                    throw new \Exception("Tinggi badan untuk pasien {$patient->nama} harus antara 92,5 - 205,0 cm (usia 61-228 bulan). (Baris " . $this->currentRowNumber . ")");
                }
                if (isset($row['lingkar_kepala']) && $row['lingkar_kepala'] && $row['lingkar_kepala'] != 0) {
                    // Set lingkar kepala ke null dan beri peringatan
                    Log::warning("Lingkar kepala tidak dinilai untuk pasien {$patient->nama} dengan usia > 60 bulan. Nilai akan diabaikan. (Baris " . $this->currentRowNumber . ")");
                    $row['lingkar_kepala'] = null;
                }
            } elseif ($totalMonths >= 121 && $totalMonths <= 228) {
                if (isset($row['berat_badan']) && $row['berat_badan'] < 18.50 || isset($row['berat_badan']) && $row['berat_badan'] > 175.00) {
                    throw new \Exception("Berat badan untuk pasien {$patient->nama} harus antara 18,50 - 175,00 kg (usia 121-228 bulan). (Baris " . $this->currentRowNumber . ")");
                }
                if (isset($row['tinggi_badan']) && $row['tinggi_badan'] < 92.5 || isset($row['tinggi_badan']) && $row['tinggi_badan'] > 205.0) {
                    throw new \Exception("Tinggi badan untuk pasien {$patient->nama} harus antara 92,5 - 205,0 cm (usia 61-228 bulan). (Baris " . $this->currentRowNumber . ")");
                }
                if (isset($row['lingkar_kepala']) && $row['lingkar_kepala'] && $row['lingkar_kepala'] != 0) {
                    // Set lingkar kepala ke null dan beri peringatan
                    Log::warning("Lingkar kepala tidak dinilai untuk pasien {$patient->nama} dengan usia > 60 bulan. Nilai akan diabaikan. (Baris " . $this->currentRowNumber . ")");
                    $row['lingkar_kepala'] = null;
                }
            }

            // Calculate IMT
            $imt = null;
            if (isset($row['berat_badan']) && $row['berat_badan'] != 0 && isset($row['tinggi_badan']) && $row['tinggi_badan'] != 0) {
                $heightInMeters = $row['tinggi_badan'] / 100;
                $imt = $row['berat_badan'] / ($heightInMeters * $heightInMeters);
                $imt = round($imt, 2);
            }


            $antroPatient = new AntroPatient([
                'patient_id' => $this->patientId,
                'tgl_periksa' => $formatTglPeriksa,
                'usia_bulan' => $totalMonths,
                'usia_hari' => $usiaHari,
                'total_usia_hari' => $totalUsiaHari,
                'berat_badan' => (isset($row['berat_badan']) && $row['berat_badan'] == 0) ? null : ($row['berat_badan'] ?? null),
                'tinggi_badan' => (isset($row['tinggi_badan']) && $row['tinggi_badan'] == 0) ? null : ($row['tinggi_badan'] ?? null),
                'lingkar_kepala' => (isset($row['lingkar_kepala']) && $row['lingkar_kepala'] == 0) ? null : ($row['lingkar_kepala'] ?? null),
                'imt' => $imt,  // Add IMT value
                'created_by' => Auth::user()->id,
                'usia_koreksi_bulan' => $usia_koreksi_bulan,
                'usia_koreksi_total_hari' => $usia_koreksi_total_hari,
                'usia_gestasi_minggu' => $usia_gestasi_minggu,
                'usia_gestasi_total_hari' => $usia_gestasi_total_hari,
            ]);

            return $antroPatient;
        } catch (\Exception $e) {
            Log::error('Error pada import antro: ' . $e->getMessage() . '. Data: ' . json_encode($row));
            throw $e;
        }
    }

    public function headingRow(): int
    {
        return $this->heading; // Heading ada di baris 15
    }

    public function rules(): array
    {
        return [
            'tgl_periksa' => ['required'],
            'berat_badan' => 'nullable|required_if:tinggi_badan,=,null|numeric|min:0|max:175',
            'tinggi_badan' => 'nullable|required_if:berat_badan,=,null|numeric|min:0|max:205',
            'lingkar_kepala' => 'nullable|numeric|min:0|max:56',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'tgl_periksa.required' => 'Tanggal periksa wajib diisi.',
            'berat_badan.required_if' => 'Berat badan wajib diisi ketika tinggi badan tidak diisi.',
            'berat_badan.numeric' => 'Berat badan harus berupa angka.',
            'berat_badan.min' => 'Berat badan minimal 0 kg.',
            'berat_badan.max' => 'Berat badan maksimal 175 kg.',
            'tinggi_badan.required_if' => 'Tinggi badan wajib diisi ketika berat badan tidak diisi.',
            'tinggi_badan.numeric' => 'Tinggi badan harus berupa angka.',
            'tinggi_badan.min' => 'Tinggi badan minimal 0 cm.',
            'tinggi_badan.max' => 'Tinggi badan maksimal 205 cm.',
            'lingkar_kepala.numeric' => 'Lingkar kepala harus berupa angka.',
            'lingkar_kepala.min' => 'Lingkar kepala minimal 0 cm.',
            'lingkar_kepala.max' => 'Lingkar kepala maksimal 56 cm.',
        ];
    }
}
