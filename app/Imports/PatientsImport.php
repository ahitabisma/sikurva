<?php

namespace App\Imports;

use App\Http\Services\PointService;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithValidation;

class PatientsImport implements ToModel, WithHeadingRow, WithValidation, WithProgressBar
{
    use Importable;

    protected $userId;
    protected $headingRow = 15; // Default seperti sebelumnya
    protected $rowCount = 0; // Menambahkan counter untuk jumlah baris
    protected $maxRows = 50; // Maksimal 50 data yang diperbolehkan


    /**
     * Parse DD-MM-YY date to YYYY-MM-DD, handling two-digit years.
     *
     * @param string $date
     * @return string|null
     */
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

    public function __construct($userId, $headingRow = null)
    {
        $this->userId = $userId;
        if ($headingRow !== null) {
            $this->headingRow = $headingRow;
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
            // Tambahkan logging lebih detail
            // Log::info('Processing row:', $row);

            // Cek apakah sudah melebihi batas maksimal 50 baris
            if (Auth::user()->hasRole('admin')) {
                if (++$this->rowCount > $this->maxRows) {
                    throw new \Exception("Maksimal import 50 data pasien. Data ke-" . $this->rowCount . " dan seterusnya tidak akan diproses.");
                }
            }

            // Cek baris kosong LEBIH KETAT - periksa kolom kunci
            if (empty($row['nama'] ?? '') && empty($row['jenis_kelamin'] ?? '') && empty($row['tgl_lahir'] ?? '')) {
                $this->rowCount--; // Kurangi lagi counternya jika ini baris kosong
                return null; // Skip baris kosong tanpa melempar error
            }

            // Normalisasi key array untuk menangani variasi format
            $row = array_change_key_case($row, CASE_LOWER);

            // Map key yang mungkin berbeda-beda
            $keyMappings = [
                'kode_mr' => ['kode_mr', 'kode mr', 'kode'],
                'nama' => ['nama', 'name', 'nama pasien', 'nama_pasien'],
                'jenis_kelamin' => ['jenis_kelamin', 'jenis kelamin', 'kelamin', 'gender'],
                'tgl_lahir' => ['tgl_lahir', 'tgl lahir', 'tanggal lahir', 'tanggal_lahir', 'dob', 'birth_date'],
                'usia_kehamilan_minggu' => ['usia_kehamilan_minggu', 'usia kehamilan minggu', 'usia kehamilan', 'usia_kehamilan'],
                'tinggi_ayah' => ['tinggi_ayah', 'tinggi ayah'],
                'tinggi_ibu' => ['tinggi_ibu', 'tinggi ibu'],
                'no_wa' => ['no_wa', 'no wa', 'whatsapp', 'wa', 'telepon', 'hp', 'handphone'],
                'email' => ['email', 'e-mail', 'mail']
            ];

            // Normalisasi key berdasarkan mapping
            $normalizedRow = [];
            foreach ($keyMappings as $standardKey => $possibleKeys) {
                foreach ($possibleKeys as $key) {
                    if (isset($row[$key])) {
                        $normalizedRow[$standardKey] = $row[$key];
                        break;
                    }
                }
            }

            // Update row dengan nilai yang dinormalisasi
            $row = $normalizedRow;

            // Pastikan semua key yang diperlukan ada
            $requiredKeys = ['nama', 'jenis_kelamin', 'tgl_lahir'];
            foreach ($requiredKeys as $key) {
                if (!array_key_exists($key, $row) || empty($row[$key])) {
                    throw new \Exception("Kolom '$key' tidak ditemukan atau kosong di file Excel.");
                }
            }

            // Validasi jenis kelamin
            if (!in_array(strtoupper($row['jenis_kelamin']), ['L', 'P'])) {
                throw new \Exception("Jenis kelamin harus L atau P, nilai ditemukan: " . $row['jenis_kelamin']);
            }

            // Normalisasi jenis kelamin
            $row['jenis_kelamin'] = strtoupper($row['jenis_kelamin']);

            // Konversi usia_kehamilan_minggu ke null jika kosong atau tidak valid
            $usiaKehamilanMinggu = isset($row['usia_kehamilan_minggu']) && $row['usia_kehamilan_minggu'] !== ''
                ? (int) $row['usia_kehamilan_minggu']
                : 40; // Default ke 40 jika tidak diisi

            if ($usiaKehamilanMinggu < 27 || $usiaKehamilanMinggu > 40) {
                // Log::warning("Usia kehamilan di luar rentang (27-40): $usiaKehamilanMinggu, default ke 40");
                $usiaKehamilanMinggu = 40;
            }

            if ($usiaKehamilanMinggu >= 37 && $usiaKehamilanMinggu <= 40) {
                $countUsiaKehamilanMinggu = 40;
            } else {
                $countUsiaKehamilanMinggu = $usiaKehamilanMinggu;
            }

            $patient = new Patient([
                'kode_lokal' => $row['kode_mr'] ?? null,
                'nama' => $row['nama'],
                'jenis_kelamin' => $row['jenis_kelamin'],
                'tgl_lahir' => $this->parseExcelDate($row['tgl_lahir']),
                'usia_kehamilan_minggu' => $usiaKehamilanMinggu,
                'count_usia_kehamilan_minggu' => $countUsiaKehamilanMinggu,
                'tinggi_ayah' => isset($row['tinggi_ayah']) && $row['tinggi_ayah'] !== '' ? (int) $row['tinggi_ayah'] : null,
                'tinggi_ibu' => isset($row['tinggi_ibu']) && $row['tinggi_ibu'] !== '' ? (int) $row['tinggi_ibu'] : null,
                'no_wa' => isset($row['no_wa']) && $row['no_wa'] !== '' ? $row['no_wa'] : null,
                'email' => isset($row['email']) && $row['email'] !== '' ? $row['email'] : null,
                'created_by' => $this->userId,
            ]);

            return $patient;
        } catch (\Exception $e) {
            Log::error('Error pada import pasien: ' . $e->getMessage() . '. Data: ' . json_encode($row));
            throw $e;
        }
    }

    public function headingRow(): int
    {
        return $this->headingRow;
    }

    public function rules(): array
    {
        return [
            'kode_lokal' => 'nullable|string|max:10',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tgl_lahir' => ['required'],
            'usia_kehamilan_minggu' => 'nullable|integer|min:27|max:40',
            'tinggi_ayah' => 'nullable|integer',
            'tinggi_ibu' => 'nullable|integer',
            'no_wa' => ['nullable', 'phone:ID', 'max:15'],
            'email' => 'nullable|email|max:255',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'kode_lokal.max' => 'Kode MR maksimal 10 karakter.',
            'kode_lokal.string' => 'Kode MR harus berupa string.',
            'nama.required' => 'Nama pasien wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib diisi.',
            'jenis_kelamin.in' => 'Jenis kelamin harus L atau P.',
            'tgl_lahir.required' => 'Tanggal lahir wajib diisi.',
            'usia_kehamilan_minggu.min' => 'Usia kehamilan minggu minimal 27.',
            'usia_kehamilan_minggu.max' => 'Usia kehamilan minggu maksimal 40.',
            'no_wa.phone' => 'Nomor WhatsApp tidak valid.',
            'no_wa.max' => 'Nomor WhatsApp maksimal 15 karakter.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
        ];
    }
}
