<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\PatientService;
use App\Http\Services\PointService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PenilaianController extends Controller
{
    protected $patientService;
    protected $pointService;

    public function __construct(PatientService $patientService, PointService $pointService)
    {
        $this->pointService = $pointService;
        $this->patientService = $patientService;
    }

    private function roundUp($number, $precision = 0)
    {
        $rounded = round($number, $precision, PHP_ROUND_HALF_UP);

        // Handle negative 0
        // Check if the rounded value is effectively zero (e.g., -0.00 or 0.00)
        if ($rounded == 0) {
            return 0; // Return exactly 0 instead of -0
        }

        return $rounded;
    }

    function normalCdf($x, $mean = 0, $stdDev = 1)
    {
        // Standardize the variable
        $z = ($x - $mean) / $stdDev;

        // For negative z, use symmetry property
        if ($z < 0) {
            return 1 - $this->normalCdf(-$z, 0, 1);
        }

        // Constants for the approximation
        $p = 0.2316419;
        $b1 = 0.319381530;
        $b2 = -0.356563782;
        $b3 = 1.781477937;
        $b4 = -1.821255978;
        $b5 = 1.330274429;

        $t = 1 / (1 + $p * $z);
        $polynomial = $b1 * $t + $b2 * pow($t, 2) + $b3 * pow($t, 3) + $b4 * pow($t, 4) + $b5 * pow($t, 5);

        return 1 - (1 / sqrt(2 * M_PI)) * exp(-pow($z, 2) / 2) * $polynomial;
    }

    function roundPercentilFromZscore($z)
    {
        // Hitung persentil dari Z-score
        $percentil = $this->normalCdf($z) * 100;

        // ROUNDUP: menjauhi nol
        if ($z < 0) {
            // ROUNDUP negatif → lebih negatif
            $round = floor($percentil * 10) / 10;
            if ($round == 100) {
                $round = 99.9;
            } elseif ($round == 0) {
                $round = '0.00';
            }

            return $round;
        } elseif ($z > 0) {
            // ROUNDUP positif → lebih positif
            $round = ceil($percentil * 10) / 10;
            if ($round == 100) {
                $round = 99.9;
            } elseif ($round == 0) {
                $round = '0.00';
            }

            return $round;
        } else {
            // Z = 0 → round biasa
            $round = round($percentil, 1);
            if ($round == 100) {
                $round = 99.9;
            } elseif ($round == 0) {
                $round = '0.00';
            }
            return $round;
        }
    }

    /**
     * Hitung Z-Score dan kategori berdasarkan data antro dan standar
     * Data dikelompokkan berdasarkan jenis pengukuran (bb, tb, lk, dll)
     *
     * @param object $antro Data antropometri pasien
     * @param array $standar Array standar referensi untuk berbagai pengukuran
     * @return array Hasil interpretasi gizi yang dikelompokkan
     */
    private function hitungZScoreDanKategori($antro, $standar)
    {
        $hasil = [
            'bb' => [],   // Berat Badan
            'tb' => [],   // Tinggi Badan
            'lk' => [],   // Lingkar Kepala
            'bbtb' => [], // BB/TB
            'imt' => [],  // IMT/U
        ];

        // BB/U (Berat Badan / Umur)
        if (isset($standar['bbu']) && $antro->berat_badan > 0) {
            $zBBU = $this->roundUp(($this->rumusA($antro->berat_badan, $standar['bbu']->l, $standar['bbu']->m, $standar['bbu']->s)), 2);
            $hasil['bb']['z_score'] = $zBBU;
            $hasil['bb']['percentil'] = $this->roundPercentilFromZscore($zBBU);

            // Kategori status gizi BB/U
            if ($zBBU < -3) {
                $hasil['bb']['kategori'] = 'BB Sangat Kurang';
            } elseif ($zBBU >= -3 && $zBBU < -2) {
                $hasil['bb']['kategori'] = 'BB Kurang';
            } elseif ($zBBU >= -2 && $zBBU <= 1) {
                $hasil['bb']['kategori'] = 'BB Normal';
            } elseif ($zBBU > 1 && $zBBU <= 2) {
                $hasil['bb']['kategori'] = 'Risiko BB Lebih';
            } else {
                $hasil['bb']['kategori'] = 'Risiko BB Lebih';
            }

            // Batas normal BB/U
            $zBatasBawahNormal = -2;
            $zBatasAtasNormal = 1;
            $bbBawahNormal = $this->rumusC(
                $standar['bbu']->m,
                $standar['bbu']->l,
                $standar['bbu']->s,
                $zBatasBawahNormal
            );
            $bbAtasNormal = $this->rumusC(
                $standar['bbu']->m,
                $standar['bbu']->l,
                $standar['bbu']->s,
                $zBatasAtasNormal
            );

            // Format hasil sesuai usia
            $hasil['bb']['batas_normal'] = [
                'bawah' => $antro->total_usia_hari <= 1856 ? $this->roundUp($bbBawahNormal, 2) : $this->roundUp($bbBawahNormal, 1),
                'atas' => $antro->total_usia_hari <= 1856 ? $this->roundUp($bbAtasNormal, 2) : $this->roundUp($bbAtasNormal, 1),
            ];

            // Data aktual
            $hasil['bb']['aktual'] = $antro->berat_badan;
        }

        // TB/U (Tinggi Badan / Umur)
        if (isset($standar['tbu']) && $antro->tinggi_badan > 0) {
            $zTBU = $this->roundUp(($this->rumusA($antro->tinggi_badan, $standar['tbu']->l, $standar['tbu']->m, $standar['tbu']->s)), 2);
            $hasil['tb']['z_score'] = $zTBU;
            $hasil['tb']['z_score_tanpa_pembulatan'] = $this->rumusA($antro->tinggi_badan, $standar['tbu']->l, $standar['tbu']->m, $standar['tbu']->s);
            $hasil['tb']['percentil'] = $this->roundPercentilFromZscore($zTBU);

            // Kategori status TB/U
            if ($zTBU < -3) {
                $hasil['tb']['kategori'] = 'Sangat Pendek';
            } elseif ($zTBU >= -3 && $zTBU < -2) {
                $hasil['tb']['kategori'] = 'TB Pendek';
            } elseif ($zTBU >= -2 && $zTBU < 3) {
                $hasil['tb']['kategori'] = 'TB Normal';
            } else {
                $hasil['tb']['kategori'] = 'TB Tinggi';
            }

            // Batas normal TB/U
            $zBatasBawahNormal = -2;
            $zBatasAtasNormal = 3;
            $tbBawahNormal = $this->rumusC(
                $standar['tbu']->m,
                $standar['tbu']->l,
                $standar['tbu']->s,
                $zBatasBawahNormal
            );
            $tbAtasNormal = $this->rumusC(
                $standar['tbu']->m,
                $standar['tbu']->l,
                $standar['tbu']->s,
                $zBatasAtasNormal
            );

            $hasil['tb']['batas_normal'] = [
                'bawah' => $this->roundUp($tbBawahNormal, 1),
                'atas' => $this->roundUp($tbAtasNormal, 1),
            ];

            // Data aktual
            $hasil['tb']['aktual'] = $antro->tinggi_badan;
        }

        // LK/U (Lingkar Kepala / Umur)
        if (isset($standar['lku']) && $antro->lingkar_kepala > 0) {
            $zLKU = $this->roundUp(($this->rumusA($antro->lingkar_kepala, $standar['lku']->l, $standar['lku']->m, $standar['lku']->s)), 2);
            $hasil['lk']['z_score'] = $zLKU;
            $hasil['lk']['percentil'] = $this->roundPercentilFromZscore($zLKU);

            // Kategori status LK/U
            if ($zLKU < -2) {
                $hasil['lk']['kategori'] = 'Microcephali';
            } elseif ($zLKU >= -2 && $zLKU < 2) {
                $hasil['lk']['kategori'] = 'LK Normal';
            } else {
                $hasil['lk']['kategori'] = 'Macrocephali';
            }

            // Batas normal LK/U
            $zBatasBawahNormal = -2;
            $zBatasAtasNormal = 2;
            $lkBawahNormal = $this->rumusC(
                $standar['lku']->m,
                $standar['lku']->l,
                $standar['lku']->s,
                $zBatasBawahNormal
            );
            $lkAtasNormal = $this->rumusC(
                $standar['lku']->m,
                $standar['lku']->l,
                $standar['lku']->s,
                $zBatasAtasNormal
            );

            $hasil['lk']['batas_normal'] = [
                'bawah' => $this->roundUp($lkBawahNormal, 1),
                'atas' => $this->roundUp($lkAtasNormal, 1),
            ];

            // Data aktual
            $hasil['lk']['aktual'] = $antro->lingkar_kepala;
        }

        // BB/TB (Berat Badan / Tinggi Badan)
        if (isset($standar['bbtb']) && $antro->tinggi_badan > 0 && $antro->berat_badan > 0) {
            $zBBTB = $this->roundUp(($this->rumusA($antro->berat_badan, $standar['bbtb']->l, $standar['bbtb']->m, $standar['bbtb']->s)), 2);
            $hasil['bbtb']['z_score'] = $zBBTB;
            $hasil['bbtb']['percentil'] = $this->roundPercentilFromZscore($zBBTB);

            // Kategori status BB/TB
            if ($antro->total_usia_hari <= 1856) {
                if ($zBBTB < -3) {
                    $hasil['bbtb']['kategori'] = 'Gizi Buruk';
                } elseif ($zBBTB >= -3 && $zBBTB < -2) {
                    $hasil['bbtb']['kategori'] = 'Gizi Kurang';
                } elseif ($zBBTB >= -2 && $zBBTB <= 1) {
                    $hasil['bbtb']['kategori'] = 'Gizi Baik';
                } elseif ($zBBTB > 1 && $zBBTB <= 2) {
                    $hasil['bbtb']['kategori'] = 'Risiko Gizi Lebih';
                } elseif ($zBBTB >= 2 && $zBBTB < 3) {
                    $hasil['bbtb']['kategori'] = 'Gizi Lebih';
                } else {
                    $hasil['bbtb']['kategori'] = 'Obesitas';
                }
            } else {
                if ($zBBTB < -3) {
                    $hasil['bbtb']['kategori'] = 'Gizi Buruk';
                } elseif ($zBBTB >= -3 && $zBBTB < -2) {
                    $hasil['bbtb']['kategori'] = 'Gizi Kurang';
                } elseif ($zBBTB >= -2 && $zBBTB <= 1) {
                    $hasil['bbtb']['kategori'] = 'Gizi Baik';
                } elseif ($zBBTB > 1 && $zBBTB <= 2) {
                    $hasil['bbtb']['kategori'] = 'Gizi Lebih';
                } else {
                    $hasil['bbtb']['kategori'] = 'Obesitas';
                }
            }

            // Batas normal BB/TB
            $zBatasBawahNormal = -2;
            $zBatasAtasNormal = 1;
            $bbtbBawahNormal = $this->rumusC(
                $standar['bbtb']->m,
                $standar['bbtb']->l,
                $standar['bbtb']->s,
                $zBatasBawahNormal
            );
            $bbtbAtasNormal = $this->rumusC(
                $standar['bbtb']->m,
                $standar['bbtb']->l,
                $standar['bbtb']->s,
                $zBatasAtasNormal
            );

            $hasil['bbtb']['batas_normal'] = [
                'bawah' => $antro->total_usia_hari <= 1856 ? $this->roundUp($bbtbBawahNormal, 2) : $this->roundUp($bbtbBawahNormal, 1),
                'atas' => $antro->total_usia_hari <= 1856 ? $this->roundUp($bbtbAtasNormal, 2) : $this->roundUp($bbtbAtasNormal, 1),
            ];
        }

        // IMT/U (Indeks Massa Tubuh / Umur)
        if (isset($standar['imtu']) && $antro->tinggi_badan > 0 && $antro->berat_badan > 0) {
            // Hitung IMT
            $imt = $antro->berat_badan / pow($antro->tinggi_badan / 100, 2);
            $hasil['imt']['nilai'] = $this->roundUp($imt, 2);

            $zIMTU = $this->roundUp(($this->rumusA($imt, $standar['imtu']->l, $standar['imtu']->m, $standar['imtu']->s)), 2);
            $hasil['imt']['z_score'] = $zIMTU;
            $hasil['imt']['percentil'] = $this->roundPercentilFromZscore($zIMTU);

            // Kategori status IMT/U
            if ($antro->total_usia_hari <= 1856) {
                if ($zIMTU < -3) {
                    $hasil['imt']['kategori'] = 'Gizi Buruk';
                } elseif ($zIMTU >= -3 && $zIMTU < -2) {
                    $hasil['imt']['kategori'] = 'Gizi Kurang';
                } elseif ($zIMTU >= -2 && $zIMTU <= 1) {
                    $hasil['imt']['kategori'] = 'Gizi Baik';
                } elseif ($zIMTU > 1 && $zIMTU <= 2) {
                    $hasil['imt']['kategori'] = 'Risiko Gizi Lebih';
                } elseif ($zIMTU >= 2 && $zIMTU < 3) {
                    $hasil['imt']['kategori'] = 'Gizi Lebih';
                } else {
                    $hasil['imt']['kategori'] = 'Obesitas';
                }
            } else {
                if ($zIMTU < -3) {
                    $hasil['imt']['kategori'] = 'Gizi Buruk';
                } elseif ($zIMTU >= -3 && $zIMTU < -2) {
                    $hasil['imt']['kategori'] = 'Gizi Kurang';
                } elseif ($zIMTU >= -2 && $zIMTU <= 1) {
                    $hasil['imt']['kategori'] = 'Gizi Baik';
                } elseif ($zIMTU > 1 && $zIMTU <= 2) {
                    $hasil['imt']['kategori'] = 'Gizi Lebih';
                } else {
                    $hasil['imt']['kategori'] = 'Obesitas';
                }
            }

            // Batas normal IMT/U
            $zBatasBawahNormal = -2;
            $zBatasAtasNormal = 1;
            $imtBatasBawahNormal = $this->rumusC(
                $standar['imtu']->m,
                $standar['imtu']->l,
                $standar['imtu']->s,
                $zBatasBawahNormal
            );
            $imtBatasAtasNormal = $this->rumusC(
                $standar['imtu']->m,
                $standar['imtu']->l,
                $standar['imtu']->s,
                $zBatasAtasNormal
            );

            // IMT setara berat badan
            $imtBawahSetara = ($imtBatasBawahNormal * pow($antro->tinggi_badan, 2)) / 10000;
            $imtAtasSetara = ($imtBatasAtasNormal * pow($antro->tinggi_badan, 2)) / 10000;

            $hasil['imt']['batas_normal'] = [
                'bawah' => $this->roundUp($imtBatasBawahNormal, 1),
                'atas' => $this->roundUp($imtBatasAtasNormal, 1),
            ];

            $hasil['imt']['batas_setara'] = [
                'bawah' => $this->roundUp($imtBawahSetara, 1),
                'atas' => $this->roundUp($imtAtasSetara, 1),
            ];
        }

        return $hasil;
    }

    private function hitungTinggiNormalLajuPertumbuhan($table, $antro)
    {
        $zTBU = $this->roundUp((pow($antro->tinggi_badan / $table->m, $table->l) - 1) / ($table->l * $table->s), 2);
        $hasil['z_tbu'] = $zTBU;
        $hasil['z_tbu_tanpa_pembulatan'] = (pow($antro->tinggi_badan / $table->m, $table->l) - 1) / ($table->l * $table->s);

        if ($zTBU < -3) {
            $hasil['kategori_tbu'] = 'Sangat Pendek';
        } elseif ($zTBU >= -3 && $zTBU < -2) {
            $hasil['kategori_tbu'] = 'Pendek';
        } else {
            $hasil['kategori_tbu'] = 'Normal';
        }

        // Nilai Z Normal diambil dari Sheet
        $zBatasBawahNormal = -2;
        $zBatasAtasNormal = 3;
        $tbBawahNormal = $table->m * pow(
            1 + ($table->l * $table->s * $zBatasBawahNormal),
            1 / $table->l
        );
        $tbAtasNormal = $table->m * pow(
            1 + ($table->l * $table->s * $zBatasAtasNormal),
            1 / $table->l
        );
        $hasil['tb_batas_normal'] = [
            'bawah' => $this->roundUp($tbBawahNormal, 1),
            'atas' => $this->roundUp($tbAtasNormal, 1),
        ];

        return $hasil;
    }

    private function hitungGenetik($data, $patient, $zTpg, $zBawah, $zAtas)
    {
        if (!$patient->tinggi_ayah || $patient->tinggi_ayah == null || $patient->tinggi_ayah == 0 || !$patient->tinggi_ibu || $patient->tinggi_ibu == null || $patient->tinggi_ibu == 0) {
            return ['tbug' => null, 'ltbug' => null, 'htbug' => null];
        }

        if ($data->total_usia_hari <= 1856) {
            $table = DB::table('table2')
                ->where('jenis_kelamin', $patient->jenis_kelamin)
                ->where('day', $data->total_usia_hari)
                ->select('m', 'l', 's')
                ->first();
        } else {
            $month = $data->usia_bulan + ($data->usia_hari / 30);
            $table = DB::table('table8')
                ->where('jenis_kelamin', $patient->jenis_kelamin)
                ->where('month', $this->roundUp($month))
                ->select('m', 'l', 's')
                ->first();
        }

        return [
            'tbug' => $this->roundUp($table->m * pow(1 + $table->l * $table->s * $zTpg, 1 / $table->l), 1),
            'ltbug' => $this->roundUp($table->m * pow(1 + $table->l * $table->s * $zBawah, 1 / $table->l), 1),
            'htbug' => $this->roundUp($table->m * pow(1 + $table->l * $table->s * $zAtas, 1 / $table->l), 1),
        ];
    }

    private function hitungZPrematur($type, $latestAntro, $mingguGestasi, $jenisKelamin)
    {
        $hasil = $numerator = $denominator = $percentil = null;
        $sex = $jenisKelamin == 'L' ? 1 : 0;

        if ($type === 'bb') {
            $numerator = log($latestAntro->berat_badan) - (2.591277 - 0.01155 * pow($mingguGestasi, 0.5) - 2201.705 * pow($mingguGestasi, -2) + 0.0911639 * $sex);

            $denominator = 0.1470258 + 505.92394 * pow($mingguGestasi, -2) - 140.0576 * pow($mingguGestasi, -2) * log($mingguGestasi);

            $hasil = $numerator / $denominator;

            $percentil = $this->normalCdf($hasil) * 100;
        } elseif ($type === 'tb') {
            $numerator = log($latestAntro->tinggi_badan) - (4.136244 - 547.0018 * pow($mingguGestasi, -2) + 0.0026066 * $mingguGestasi + 0.0314961 * $sex);

            $denominator = 0.050489 + 310.44761 * pow($mingguGestasi, -2) - 90.0742 * pow($mingguGestasi, -2) * log($mingguGestasi);

            $hasil = $numerator / $denominator;

            $percentil = $this->normalCdf($hasil) * 100;
        } elseif ($type === 'lk') {
            $numerator = $latestAntro->lingkar_kepala - (55.53617 - 852.0059 * pow($mingguGestasi, -1) + 0.7957903 * $sex);

            $denominator = 3.0582292 + 3910.05 * pow($mingguGestasi, -2) - 180.5623 * pow($mingguGestasi, -1);

            $hasil = $numerator / $denominator;

            $percentil = $this->normalCdf($hasil) * 100;
        }


        return [
            'numerator' => $numerator ?? null,
            'denominator' => $denominator ?? null,
            'z_score' => $hasil ? $this->roundUp($hasil, 2) : null,
            'z_score_tanpa_pembulatan' => $hasil ?? null,
            'percentil' => $percentil ? $this->roundPercentilFromZscore($hasil) : null,
            'percentil_tanpa_pembulatan' => $percentil ?? null,
        ];
    }

    private function hitungZPrematurBbtb(array $zScores, float $beratBadan = null): array
    {
        // If no weight is provided or if the array is empty, return a default structure
        if ($beratBadan === null || empty($zScores)) {
            return [
                'bawah' => null,
                'atas' => null,
                'text' => "",
                'percentil_bawah' => null,
                'percentil_atas' => null,
                'percentil_text' => ""
            ];
        }

        foreach ($zScores as $i => $value) {
            if ($value === null) {
                continue; // Skip null values in the array
            }

            if ($beratBadan < $value) {
                $bawah = max($i - 4, -3);
                $atas = max($i - 3, -2);
                $percentilBawah = $this->roundUp($this->normalCdf($bawah) * 100, 2); // dari Z bawah
                $percentilAtas = $this->roundUp($this->normalCdf($atas) * 100, 2); // dari Z atas

                return [
                    'bawah' => $bawah,
                    'atas' => $atas,
                    'text' => "{$bawah} s/d {$atas}",
                    'percentil_bawah' => $percentilBawah,
                    'percentil_atas' => $percentilAtas,
                    'percentil_text' => "{$percentilBawah} s/d {$percentilAtas}"
                ];
            }
        }

        // Jika berat badan > semua Z score
        $z = 3;
        $percentil = $this->roundUp($this->normalCdf($z) * 100, 2);
        return [
            'bawah' => $z,
            'atas' => null,
            'text' => "{$z} ke atas",
            'percentil_bawah' => $percentil,
            'percentil_atas' => null,
            'percentil_text' => $percentil,
        ];
    }

    // Hitung kenaikan per minggu
    private function hitungZPerMinggu($type, $jenisKelamin, $usiaMingguGestasi)
    {
        $sex = $jenisKelamin === 'L' ? 1 : 0;
        $hasil = null;

        if ($type === 'bb') {
            $constant = 2.591277;
            $sqrtTerm = -0.01155 * sqrt($usiaMingguGestasi);
            $squareTerm = -2201.705 * pow($usiaMingguGestasi, -2);
            $genderTerm = 0.0911639 * $sex;

            $exponent = $constant + $sqrtTerm + $squareTerm + $genderTerm;
            $hasil = exp($exponent);
        } elseif ($type === 'tb') {
            $constant = 4.136244;
            $sqrtTerm = -547.0018 * pow($usiaMingguGestasi, -2);
            $squareTerm = 0.0026066 * $usiaMingguGestasi;
            $genderTerm = 0.0314961 * $sex;

            $exponent = $constant + $sqrtTerm + $squareTerm + $genderTerm;
            $hasil = exp($exponent);
        } elseif ($type === 'lk') {
            $constant = 55.53617;
            $sqrtTerm = -852.0059 * pow($usiaMingguGestasi, -1);
            $squareTerm = 0.7957903 * $sex;

            $exponent = $constant + $sqrtTerm + $squareTerm;
            $hasil = ($exponent);
        }

        return $hasil;
    }


    /**
     * Calculate normal growth rate based on age in months and gender
     *
     * @param int $totalUsiaBulan Age in months
     * @param string $jenisKelamin Gender ('L' for male, 'P' for female)
     * @return array Array containing lower and upper bounds, description and formatted text
     */
    //  Hitung Normal Laju Pertumuhan Berdasarkan Mid Date
    private function hitungNormalLajuPertumbuhanMidDate($totalUsiaBulan, $jenisKelamin)
    {
        $normalLajuPertumbuhanBawah = 0;
        $normalLajuPertumbuhanAtas = 0;
        $normalLajuKeterangan = '';

        if ($totalUsiaBulan <= 12) {
            // 0-12 bulan: 23-27 cm/tahun
            $normalLajuPertumbuhanBawah = 23;
            $normalLajuPertumbuhanAtas = 27;
            $normalLajuKeterangan = '0-12 bl';
        } elseif ($totalUsiaBulan <= 24) {
            // 1-2 tahun: 10-14 cm/tahun
            $normalLajuPertumbuhanBawah = 10;
            $normalLajuPertumbuhanAtas = 14;
            $normalLajuKeterangan = '1-2 th';
        } elseif ($totalUsiaBulan <= 36) {
            // 2-3 tahun: 8 cm/tahun
            $normalLajuPertumbuhanBawah = 8;
            $normalLajuPertumbuhanAtas = 8;
            $normalLajuKeterangan = '2-3 th';
        } elseif ($totalUsiaBulan <= 60) {
            // 3-5 tahun: 7 cm/tahun
            $normalLajuPertumbuhanBawah = 7;
            $normalLajuPertumbuhanAtas = 7;
            $normalLajuKeterangan = '3-5 th';
        } elseif ($jenisKelamin === 'L' && $totalUsiaBulan < 120) {
            // 5 tahun sampai pubertas (pra-pubertas): 5-6 cm/tahun
            $normalLajuPertumbuhanBawah = 5;
            $normalLajuPertumbuhanAtas = 6;
            $normalLajuKeterangan = '5 th sd pubertas (pra-pubertas)';
        } elseif ($jenisKelamin === 'P' && $totalUsiaBulan < 96) {
            // 5 tahun sampai pubertas (pra-pubertas): 5-6 cm/tahun
            $normalLajuPertumbuhanBawah = 5;
            $normalLajuPertumbuhanAtas = 6;
            $normalLajuKeterangan = '5 th sd pubertas (pra-pubertas)';
        } elseif ($jenisKelamin === 'L' && $totalUsiaBulan >= 120 && $totalUsiaBulan <= 168) {
            // Pubertas laki-laki (10-14 tahun)
            $normalLajuPertumbuhanBawah = 10;
            $normalLajuPertumbuhanAtas = 14;
            $normalLajuKeterangan = 'Pubertas laki-laki (10-14 th)';
        } elseif ($jenisKelamin === 'P' && $totalUsiaBulan >= 96 && $totalUsiaBulan <= 144) {
            // Pubertas perempuan (8-12 tahun)
            $normalLajuPertumbuhanBawah = 10;
            $normalLajuPertumbuhanAtas = 14;
            $normalLajuKeterangan = 'Pubertas perempuan (8-12 th)';
        } else {
            // Default for older ages
            $normalLajuPertumbuhanBawah = 2;
            $normalLajuPertumbuhanAtas = 4;
            $normalLajuKeterangan = 'Pasca pubertas';
        }

        return [
            'bawah' => $normalLajuPertumbuhanBawah,
            'atas' => $normalLajuPertumbuhanAtas,
            'keterangan' => $normalLajuKeterangan,
            'text' => ($normalLajuPertumbuhanBawah == $normalLajuPertumbuhanAtas)
                ? "$normalLajuPertumbuhanBawah cm/th"
                : "$normalLajuPertumbuhanBawah - $normalLajuPertumbuhanAtas cm/th"
        ];
    }

    // N LJUG.   = ((TBUG 1- TBUG 2) / (TGL PERIKSA 1-TGL PERIKSA 2)) * 365 CM/THN
    // Tgl periksa dalam bentuk hari
    private function hitungNormalLajuPertumbuhan($tbug1, $tbug2, $tglPeriksa1, $tglPeriksa2)
    {
        $normalLajuPertumbuhan = (($tbug1 - $tbug2) / ($tglPeriksa1 - $tglPeriksa2)) * 365;

        return [
            'nilai' => $this->roundUp($normalLajuPertumbuhan, 2),
            'nilai_tanpa_pembulatan' => $normalLajuPertumbuhan
        ];
    }

    private function usiaBulanAntro($dataAntro)
    {
        return $dataAntro->usia_bulan + ($dataAntro->usia_hari / 30);
    }

    // rumus A = ((x/M) ^ L) - 1
    private function rumusA($nilai, $l, $m, $s)
    {
        $hasil = (pow($nilai / $m, $l) - 1) / ($l * $s);
        return $hasil;
    }

    // rumus C = M (1 + LSZ) ^ 1/L
    private function rumusC($m, $l, $s, $z)
    {
        $hasil = ($m * pow(1 + ($l * $s * $z), 1 / $l));
        return $hasil;
    }

    public function prosesPenilaian($selectedPoints, $patientId)
    {
        try {
            if (count($selectedPoints) === 0) {
                return false;
            }

            // Data antro pasien berdasarkan id yang dipilih
            $antros = DB::table('antro_patients')
                ->whereIn('id', array_column($selectedPoints, 'id'))
                ->orderBy('tgl_periksa', 'desc')
                ->get();

            // Data pasien berdasarkan id
            $patient = $this->patientService->findById($patientId);
            $patient->created_by_name = DB::table('users')->where('id', $patient->created_by)->value('name');

            // Data paling baru berdasarkan tgl_periksa
            $latestAntro = $antros->first();
            // Usia Bulan dari data tgl periksa terbaru
            $usiaBulanLatestAntro = $this->usiaBulanAntro($latestAntro);
            // Pembulatan usia bulan dari data tgl periksa terbaru
            $usiaBulanPembulatan = $this->roundUp($usiaBulanLatestAntro, 0);

            // A. Hitung Nilai Z BB/U dari data tgl periksa terbaru
            $tableBbu = $tableTbu = $tableLku = $tableBbtb = $tableImtu = $tableBbtbPrematur = null;

            // Jika usia kehamilan minggu >= 37 mg
            if ($patient->usia_kehamilan_minggu >= 37) {
                // Jika usia sebenarnya <= 5 thn
                if ($latestAntro->total_usia_hari <= 1856) {
                    $tableBbu = DB::table('table1')
                        ->where('jenis_kelamin', $patient->jenis_kelamin)
                        ->where('day', $latestAntro->total_usia_hari)
                        ->select('m', 'l', 's', 'sd0')
                        ->first();
                    $tableTbu = DB::table('table2')
                        ->where('jenis_kelamin', $patient->jenis_kelamin)
                        ->where('day', $latestAntro->total_usia_hari)
                        ->select('m', 'l', 's', 'sd0')
                        ->first();
                    $tableLku = DB::table('table3')
                        ->where('jenis_kelamin', $patient->jenis_kelamin)
                        ->where('day', $latestAntro->total_usia_hari)
                        ->select('m', 'l', 's')
                        ->first();
                    $tableBbtb = DB::table('table4')
                        ->where('jenis_kelamin', $patient->jenis_kelamin)
                        ->where('length', $latestAntro->tinggi_badan)
                        ->select('m', 'l', 's')
                        ->first();
                    $tableImtu = DB::table('table5')
                        ->where('jenis_kelamin', $patient->jenis_kelamin)
                        ->where('day', $latestAntro->total_usia_hari)
                        ->select('m', 'l', 's')
                        ->first();
                }
                // Jika usia sebenarnya 5-19 thn
                elseif ($latestAntro->total_usia_hari > 1856 && $latestAntro->total_usia_hari <= 6935) {
                    $tableTbu = DB::table('table8')
                        ->where('jenis_kelamin', $patient->jenis_kelamin)
                        ->where('month', $usiaBulanPembulatan)
                        ->select('m', 'l', 's', 'sd0')
                        ->first();
                    $tableImtu = DB::table('table7')
                        ->where('jenis_kelamin', $patient->jenis_kelamin)
                        ->where('month', $usiaBulanPembulatan)
                        ->select('m', 'l', 's', 'sd0')
                        ->first();

                    // Jika usia sebenarnya 5-10 thn
                    if ($latestAntro->total_usia_hari > 1856 && $latestAntro->total_usia_hari <= 3650) {
                        $tableBbu = DB::table('table6')
                            ->where('jenis_kelamin', $patient->jenis_kelamin)
                            ->where('month', $usiaBulanPembulatan)
                            ->select('m', 'l', 's', 'sd0')
                            ->first();
                    }
                }
            }
            // Jika usia kehamilan minggu 27 <= x <= 36 mg
            elseif ($patient->usia_kehamilan_minggu >= 27 && $patient->usia_kehamilan_minggu <= 36) {
                // Jika usia kronologis / US <= 24 bln dan PMA < 64 mg
                if ($latestAntro->total_usia_hari <= 730 && $latestAntro->usia_gestasi_minggu != null &&  $latestAntro->usia_gestasi_minggu < 64) {
                    $tableBbtbPrematur = DB::table('table12')
                        ->where('jenis_kelamin', $patient->jenis_kelamin)
                        ->where('length', $latestAntro->tinggi_badan)
                        ->first();

                    // Table tbu biar tbug ga null
                    $tableTbu = DB::table('table2')
                        ->where('jenis_kelamin', $patient->jenis_kelamin)
                        ->where('day', $latestAntro->total_usia_hari)
                        ->select('m', 'l', 's', 'sd0')
                        ->first();
                }
                // Jika usia kronologis / US <= 24 bln dan PMA > 64 mg
                elseif ($latestAntro->total_usia_hari <= 730 && ($latestAntro->usia_gestasi_minggu == null || $latestAntro->usia_gestasi_minggu > 64)) {
                    $tableBbu = DB::table('table1')
                        ->where('jenis_kelamin', $patient->jenis_kelamin)
                        ->where('day', $latestAntro->usia_koreksi_total_hari)
                        ->select('m', 'l', 's', 'sd0')
                        ->first();
                    $tableTbu = DB::table('table2')
                        ->where('jenis_kelamin', $patient->jenis_kelamin)
                        ->where('day', $latestAntro->usia_koreksi_total_hari)
                        ->select('m', 'l', 's', 'sd0')
                        ->first();
                    $tableLku = DB::table('table3')
                        ->where('jenis_kelamin', $patient->jenis_kelamin)
                        ->where('day', $latestAntro->usia_koreksi_total_hari)
                        ->select('m', 'l', 's')
                        ->first();
                    $tableBbtb = DB::table('table4')
                        ->where('jenis_kelamin', $patient->jenis_kelamin)
                        ->where('length', $latestAntro->tinggi_badan)
                        ->select('m', 'l', 's')
                        ->first();
                    $tableImtu = DB::table('table5')
                        ->where('jenis_kelamin', $patient->jenis_kelamin)
                        ->where('day', $latestAntro->usia_koreksi_total_hari)
                        ->select('m', 'l', 's')
                        ->first();
                }
                // Jika usia sebenarnya > 24 bln UK = US
                elseif ($latestAntro->total_usia_hari > 730) {
                    if ($latestAntro->total_usia_hari <= 1856) {
                        $tableBbu = DB::table('table1')
                            ->where('jenis_kelamin', $patient->jenis_kelamin)
                            ->where('day', $latestAntro->total_usia_hari)
                            ->select('m', 'l', 's', 'sd0')
                            ->first();
                        $tableTbu = DB::table('table2')
                            ->where('jenis_kelamin', $patient->jenis_kelamin)
                            ->where('day', $latestAntro->total_usia_hari)
                            ->select('m', 'l', 's', 'sd0')
                            ->first();
                        $tableLku = DB::table('table3')
                            ->where('jenis_kelamin', $patient->jenis_kelamin)
                            ->where('day', $latestAntro->total_usia_hari)
                            ->select('m', 'l', 's')
                            ->first();
                        $tableBbtb = DB::table('table4')
                            ->where('jenis_kelamin', $patient->jenis_kelamin)
                            ->where('length', $latestAntro->tinggi_badan)
                            ->select('m', 'l', 's')
                            ->first();
                        $tableImtu = DB::table('table5')
                            ->where('jenis_kelamin', $patient->jenis_kelamin)
                            ->where('day', $latestAntro->total_usia_hari)
                            ->select('m', 'l', 's')
                            ->first();
                    }
                    // Jika usia sebenarnya 5-19 thn
                    elseif ($latestAntro->total_usia_hari > 1856 && $latestAntro->total_usia_hari <= 6935) {
                        $tableTbu = DB::table('table8')
                            ->where('jenis_kelamin', $patient->jenis_kelamin)
                            ->where('month', $usiaBulanPembulatan)
                            ->select('m', 'l', 's', 'sd0')
                            ->first();
                        $tableImtu = DB::table('table7')
                            ->where('jenis_kelamin', $patient->jenis_kelamin)
                            ->where('month', $usiaBulanPembulatan)
                            ->select('m', 'l', 's', 'sd0')
                            ->first();

                        // Jika usia sebenarnya 5-10 thn
                        if ($latestAntro->total_usia_hari > 1856 && $latestAntro->total_usia_hari <= 3650) {
                            $tableBbu = DB::table('table6')
                                ->where('jenis_kelamin', $patient->jenis_kelamin)
                                ->where('month', $usiaBulanPembulatan)
                                ->select('m', 'l', 's', 'sd0')
                                ->first();
                        }
                    }
                }
            }

            $standar = [
                'bbu'   => $tableBbu,   // LMS BB/U
                'tbu'   => $tableTbu,   // LMS TB/U
                'lku'   => $tableLku,   // LMS LK/U
                'bbtb'  => $tableBbtb,  // LMS BB/TB
                'imtu'  => $tableImtu,  // LMS IMT/U
            ];


            // Hasil Z-Score berdasarkan tgl periksa terbaru
            $interpretasiGizi = $this->hitungZScoreDanKategori($latestAntro, $standar);
            // dd($interpretasiGizi);

            // Handle nilai z-score
            if ($latestAntro->total_usia_hari > 1856 && $latestAntro->total_usia_hari <= 6935) {
                if ($latestAntro->tinggi_badan > 0 && $latestAntro->berat_badan > 0) {
                    $interpretasiGizi['bbtb']['z_score'] = '**';
                }

                if ($latestAntro->lingkar_kepala > 0) {
                    $interpretasiGizi['lk']['z_score'] = '**';
                }

                if ($latestAntro->total_usia_hari > 3650 && $latestAntro->berat_badan > 0) {
                    $interpretasiGizi['bb']['z_score'] = '***';
                }
            }

            // Z Score jika usia kehamilan minggu 27 - 36 mg && US <= 180 hari
            if ($patient->usia_kehamilan_minggu <= 36 && $patient->usia_kehamilan_minggu >= 27 && $latestAntro->usia_gestasi_total_hari != null && $latestAntro->usia_gestasi_minggu < 64) {
                // PERHITUNGAN MINGGU GESTASI BALIK SEPERTI SEMULA TOTAL HARI GESTASI / 7
                // GANTI2 MULU!!! PUSING BOSSS!!!

                $mingguGestasi = $latestAntro->usia_gestasi_total_hari / 7;

                // BB/U
                // [$mingguGestasi, $hariGestasi] = convertDaysToWeek(
                //     $latestAntro->tgl_periksa ?? now(),
                //     $latestAntro->usia_gestasi_total_hari,
                // );

                // Apply rounding rule: if days >= 4, round up to next complete week
                // if ($hariGestasi >= 4) {
                //     $mingguGestasi += 1;
                //     $hariGestasi = 0;
                // } elseif ($hariGestasi < 4) {
                //     $hariGestasi = 0;
                // }

                $jenisKelamin = $patient->jenis_kelamin;
                $tipeAntropometri = ['bb', 'tb', 'lk'];
                $data = [];

                foreach ($tipeAntropometri as $tipe) {
                    $z = $this->hitungZPrematur($tipe, $latestAntro, $mingguGestasi, $jenisKelamin);
                    $data[$tipe] = [
                        'numerator' => $z['numerator'],
                        'denominator' => $z['denominator'],
                        'z_score' => $z['z_score'],
                        'z_score_tanpa_pembulatan' => $z['z_score_tanpa_pembulatan'],
                        'percentil' => $z['percentil'],
                        'percentil_tanpa_pembulatan' => $z['percentil_tanpa_pembulatan'],
                    ];
                }

                $interpretasiGizi['bb'] = $data['bb'];
                $interpretasiGizi['tb'] = $data['tb'];
                $interpretasiGizi['lk'] = $data['lk'];

                // BBTB
                // TODO: Implement BBTB calculation for premature babies
                // Safe handling for premature BBTB calculation
                if ($tableBbtbPrematur) {
                    $zScoresBBtbPrematur = [
                        (float) $tableBbtbPrematur->z3neg ?? null,
                        (float) $tableBbtbPrematur->z2neg ?? null,
                        (float) $tableBbtbPrematur->z1neg ?? null,
                        (float) $tableBbtbPrematur->z0 ?? null,
                        (float) $tableBbtbPrematur->z1 ?? null,
                        (float) $tableBbtbPrematur->z2 ?? null,
                        (float) $tableBbtbPrematur->z3 ?? null,
                    ];

                    $zRange = $this->hitungZPrematurBbtb($zScoresBBtbPrematur, $latestAntro->berat_badan);
                } else {
                    $zRange = [
                        'text' => "",
                        'percentil_text' => ""
                    ];
                }

                $interpretasiGizi['bbtb']['z_score'] = isset($zRange['text']) ? $zRange['text'] : null;
                $interpretasiGizi['bbtb']['percentil'] = isset($zRange['percentil_text']) ? $zRange['percentil_text'] : null;

                // dd([$mingguGestasi, $interpretasiGizi]);

                // Kenaikan per minggu
                $kenaikanBb = $this->hitungZPerMinggu('bb', $patient->jenis_kelamin, $mingguGestasi);
                $kenaikanBbPlusSatu = $this->hitungZPerMinggu('bb', $patient->jenis_kelamin, $mingguGestasi + 1);
                $kenaikanTb = $this->hitungZPerMinggu('tb', $patient->jenis_kelamin, $mingguGestasi);
                $kenaikanTbPlusSatu = $this->hitungZPerMinggu('tb', $patient->jenis_kelamin, $mingguGestasi + 1);
                $kenaikanLk = $this->hitungZPerMinggu('lk', $patient->jenis_kelamin, $mingguGestasi);
                $kenaikanLkPlusSatu = $this->hitungZPerMinggu('lk', $patient->jenis_kelamin, $mingguGestasi + 1);

                $interpretasiGizi['kenaikan_per_minggu'] = [
                    'bb' => $kenaikanBbPlusSatu - $kenaikanBb,
                    'tb' => $kenaikanTbPlusSatu - $kenaikanTb,
                    'lk' => $kenaikanLkPlusSatu - $kenaikanLk,
                ];
            }

            // BB Ideal untuk prematur
            if ($patient->usia_kehamilan_minggu <= 36 && $patient->usia_kehamilan_minggu >= 27 && $latestAntro->tinggi_badan != null) {
                if ($tableBbtbPrematur === null) {
                    $tableBbtbPrematur = DB::table('table12')
                        ->where('jenis_kelamin', $patient->jenis_kelamin)
                        ->where('length', $latestAntro->tinggi_badan)
                        ->first();
                }
                $interpretasiGizi['bbtb']['bb_ideal'] = $tableBbtbPrematur ? $this->roundUp($tableBbtbPrematur->z0, 2) : null;
            }
            // BB ideal untuk normal
            else {
                if ($latestAntro->total_usia_hari <= 1856 && isset($standar['bbtb'])) {
                    // BB Ideal
                    $bbIdeal = $this->rumusC(
                        $standar['bbtb']->m,
                        $standar['bbtb']->l,
                        $standar['bbtb']->s,
                        0
                    );
                    $interpretasiGizi['bbtb']['bb_ideal'] = $this->roundUp($bbIdeal, 2);
                } elseif ($latestAntro->total_usia_hari > 1856 && $latestAntro->total_usia_hari <= 6935) {
                    // BB Ideal
                    if (isset($standar['imtu']) && $standar['imtu']->sd0) {
                        $bbIdeal = $standar['imtu']->sd0 * pow($latestAntro->tinggi_badan, 2) / 10000;
                        $interpretasiGizi['bbtb']['bb_ideal'] = $this->roundUp($bbIdeal, 2);
                    }
                }
            }

            // Weight Age dan Height Age jika usia latestAntro <= 60 bln/1856 hari satuan dalam bulan
            $beratBadan = $this->roundUp((float) $latestAntro->berat_badan, 2); // 2 desimal
            $tinggiBadan = $this->roundUp((float) $latestAntro->tinggi_badan, 1); // 1 desimal

            // dd($beratBadan, $tinggiBadan);

            $tableWa = DB::table('table1')
                ->where('jenis_kelamin', $patient->jenis_kelamin)
                ->where('sd0', '>=', $beratBadan)
                ->orderBy('sd0') // ambil nilai terkecil yang lebih besar dari berat
                ->select('day')
                ->first();

            $tableHa = DB::table('table2')
                ->where('jenis_kelamin', $patient->jenis_kelamin)
                ->where('sd0', '>=', $tinggiBadan)
                ->orderBy('sd0')
                ->select('day')
                ->first();

            // B. Weight Age dan Height Age
            $wa = $ha = null;
            if (!is_null($tableWa) && !is_null($tableHa) && $latestAntro->berat_badan > 0 && $latestAntro->tinggi_badan > 0) {
                if ($latestAntro->total_usia_hari <= 1856) {
                    $wa = $this->roundUp(($tableWa->day / 30.4375), 1);
                    $ha = $this->roundUp(($tableHa->day / 30.4375), 1);

                    // Penilaian Status Gizi IMT/U dan BB/TB akan Berubah menjadi "Kemungkinan Stunting"
                    if (isset($interpretasiGizi['tb']) && isset($interpretasiGizi['tb']['z_score']) && $interpretasiGizi['tb']['z_score'] < -2 && $wa < $ha && $ha < $usiaBulanLatestAntro) {
                        if (isset($interpretasiGizi['imt'])) {
                            $interpretasiGizi['imt']['kategori'] = 'Kemungkinan Stunting';
                        }

                        if (isset($interpretasiGizi['bbtb'])) {
                            $interpretasiGizi['bbtb']['kategori'] = 'Kemungkinan Stunting';
                        }
                    }
                }
            }

            // dd($tableWa, $tableHa, $wa, $ha);

            // Tambahkan weight age dan height age ke hasil untuk ditampilkan
            $interpretasiGizi['weight_age'] = $wa;
            $interpretasiGizi['height_age'] = $ha;
            $interpretasiGizi['usia_bulan'] = $usiaBulanPembulatan;

            // C. Kenaikan Yg Diharapkan
            // Kenaikan yg diharapkan untuk anak usia dibawah <= 22 bulan
            $kenaikan = [];
            if ($this->roundUp($usiaBulanLatestAntro, 1) <= 22) {
                $searchKenaikan = DB::table('tabel_kenaikans')
                    ->where('jenis_kelamin', $patient->jenis_kelamin)
                    ->where('usia_bulan', '=', $usiaBulanPembulatan)
                    ->first();

                $kenaikan = [
                    'bb_bawah' => $latestAntro->berat_badan > 0 ? $searchKenaikan->bb_bawah : null,
                    'bb_atas' => $latestAntro->berat_badan > 0 ? $searchKenaikan->bb_atas : null,
                    'bb_unit' => $latestAntro->berat_badan > 0 ? $searchKenaikan->bb_unit : null,
                    'tb_bawah' => $latestAntro->tinggi_badan > 0 ? $searchKenaikan->tb_bawah : null,
                    'tb_atas' => $latestAntro->tinggi_badan > 0 ? $searchKenaikan->tb_atas : null,
                    'tb_unit' => $latestAntro->tinggi_badan > 0 ? $searchKenaikan->tb_unit : null,
                    'lk_bawah' => $latestAntro->lingkar_kepala > 0 ? $searchKenaikan->lk_bawah : null,
                    'lk_atas' => $latestAntro->lingkar_kepala > 0 ? $searchKenaikan->lk_atas : null,
                    'lk_unit' => $latestAntro->lingkar_kepala > 0 ? $searchKenaikan->lk_unit : null,
                ];
            }

            $interpretasiGizi['kenaikan'] = $kenaikan;

            // D. Tinggi Potensi Genetik
            $tpg = $tinggiPerkiraanBawah = $tinggiPerkiraanAtas = $zTpg = $zTinggiPerkiraanBawah = $zTinggiPerkiraanAtas = null;

            // Hitung TPG jika tinggi badan orang tua ada
            if ($patient->tinggi_ayah && $patient->tinggi_ibu) {
                if ($patient->jenis_kelamin === 'L') {
                    // TPG LAKI-LAKI
                    // TPG = (TB Ibu (cm) + 13 cm) + TB Ayah (cm) / 2
                    $tpg = ((($patient->tinggi_ibu + 13) + $patient->tinggi_ayah) / 2);

                    // Z-Score TPG = ((TPG - / 176,5432*)-1)/0,04134*  (* default untuk anak laki)
                    $zTpg = (($tpg / 176.5432) - 1) / (0.04134);

                    // Hitung tinggi perkiraan bawah dan atas
                    $tinggiPerkiraanBawah = $this->roundUp(($tpg - 8.5), 1);
                    $tinggiPerkiraanAtas = $this->roundUp(($tpg + 8.5), 1);

                    // Hitung Z Tinggi Perkiraan
                    $zTinggiPerkiraanBawah = ((($tpg - 8.5) / 176.5432) - 1) / (0.04134);
                    $zTinggiPerkiraanAtas = ((($tpg + 8.5) / 176.5432) - 1) / (0.04134);
                } else {
                    // TPG Perempuan
                    // TPG = (TB Ayah (cm) - 13 cm) + TB Ibu (cm) / 2
                    $tpg = ((($patient->tinggi_ayah - 13) + $patient->tinggi_ibu) / 2);

                    // Z-Score TPG = ((TPG - / 163,1548*)-1)/0,04009 *  (* default untuk anak perempuan)
                    $zTpg = (($tpg / 163.1548) - 1) / (0.04009);

                    // Hitung tinggi perkiraan bawah dan atas
                    $tinggiPerkiraanBawah = $this->roundUp(($tpg - 8.5), 1);
                    $tinggiPerkiraanAtas = $this->roundUp(($tpg + 8.5), 1);

                    // Hitung Z Tinggi Perkiraan
                    $zTinggiPerkiraanBawah = (((($tpg - 8.5) / 163.1548) - 1) / (0.04009));
                    $zTinggiPerkiraanAtas = (((($tpg + 8.5) / 163.1548) - 1) / (0.04009));
                }
            }

            $interpretasiGizi['tinggi_potensi_genetik'] = [
                'tpg' => $tpg,
                'tinggi_perkiraan_bawah' => $tinggiPerkiraanBawah,
                'tinggi_perkiraan_atas' => $tinggiPerkiraanAtas,
                'z_tpg' => $zTpg,
                'z_tinggi_perkiraan_bawah' => $zTinggiPerkiraanBawah,
                'z_tinggi_perkiraan_atas' => $zTinggiPerkiraanAtas,
            ];

            // dd($interpretasiGizi);

            // TBUG = dihitung dari rumus c) = M (1 + LSZ) ^ 1/L. (TABEL 2 karena usia <=60, bila usia >60 pake tabel 8)
            $tbug = $ltbug = $htbug = null;
            if ($latestAntro->total_usia_hari <= 1856 && $patient->tinggi_ayah && $patient->tinggi_ibu && isset($standar['tbu'])) {
                $tbug = $this->roundUp($this->rumusC($standar['tbu']->m, $standar['tbu']->l, $standar['tbu']->s, $zTpg), 1);
                $ltbug = $this->roundUp($this->rumusC($standar['tbu']->m, $standar['tbu']->l, $standar['tbu']->s, $zTinggiPerkiraanBawah), 1);
                $htbug = $this->roundUp($this->rumusC($standar['tbu']->m, $standar['tbu']->l, $standar['tbu']->s, $zTinggiPerkiraanAtas), 1);
            } elseif ($latestAntro->total_usia_hari > 1856 && $patient->tinggi_ayah && $patient->tinggi_ibu) {
                $table8 = DB::table('table8')
                    ->where('jenis_kelamin', $patient->jenis_kelamin)
                    ->where('month', $usiaBulanPembulatan)
                    ->select('m', 'l', 's')
                    ->first();

                $standar['tbu'] = $table8;

                $tbug = $this->roundUp($this->rumusC($standar['tbu']->m, $standar['tbu']->l, $standar['tbu']->s, $zTpg), 1);
                $ltbug = $this->roundUp($this->rumusC($standar['tbu']->m, $standar['tbu']->l, $standar['tbu']->s, $zTinggiPerkiraanBawah), 1);
                $htbug = $this->roundUp($this->rumusC($standar['tbu']->m, $standar['tbu']->l, $standar['tbu']->s, $zTinggiPerkiraanAtas), 1);
            }

            // Proyeksi Tinggi Akhir
            $proyeksiTinggiAkhir = null;
            if (isset($interpretasiGizi['tb']) && isset($interpretasiGizi['tb']['z_score_tanpa_pembulatan'])) {
                // Laki-laki
                // 176,54 * ( 1 + (0,04134 * z))
                if ($patient->jenis_kelamin === 'L') {
                    $proyeksiTinggiAkhir = $this->roundUp(176.54 * (1 + (0.04134 * $interpretasiGizi['tb']['z_score_tanpa_pembulatan'])), 1);
                } else {
                    // Perempuan
                    // 163,15 * ( 1 + (0,04009 * z))
                    $proyeksiTinggiAkhir = $this->roundUp(163.15 * (1 + (0.04009 * $interpretasiGizi['tb']['z_score_tanpa_pembulatan'])), 1);
                }
            }
            // Initialize the tbug structure in $interpretasiGizi
            $interpretasiGizi['tbug'] = [
                'nilai' => $tbug,
                'batas' => [
                    'bawah' => $ltbug,
                    'atas' => $htbug
                ],
                'proyeksi_tinggi_akhir' => $proyeksiTinggiAkhir ?? null,
                'summary' => null,
                'status' => null,
                'range' => null,
                'status_range' => null
            ];

            if ($latestAntro->tinggi_badan && $tbug) {
                // Format output sesuai permintaan
                $selisih = $this->roundUp(($tbug - $latestAntro->tinggi_badan), 1);
                $selisihAbs = abs($selisih);

                // Tinggi Potensi Genetik Usia X Bln = Y
                $interpretasiGizi['tbug']['summary'] = "Tinggi Potensi Genetik Usia {$usiaBulanPembulatan} Bln = {$tbug}";

                // Status tinggi dibanding potensi genetik
                if ($selisih > 0) {
                    // $interpretasiGizi['tbug']['status'] = "Tinggi anak {$selisihAbs} cm dibawah potensi genetiknya, {$selisihAbs} didapat dari {$tbug} - {$latestAntro->tinggi_badan}";
                    $interpretasiGizi['tbug']['status'] = "Tinggi anak {$selisihAbs} cm dibawah potensi genetiknya.";
                } elseif ($selisih < 0) {
                    // $interpretasiGizi['tbug']['status'] = "Tinggi anak {$selisihAbs} cm diatas potensi genetiknya, {$selisihAbs} didapat dari {$latestAntro->tinggi_badan} - {$tbug}";
                    $interpretasiGizi['tbug']['status'] = "Tinggi anak {$selisihAbs} cm diatas potensi genetiknya.";
                } else {
                    $interpretasiGizi['tbug']['status'] = "Tinggi anak sama dengan potensi genetiknya";
                }

                // Range dan status dalam range
                $interpretasiGizi['tbug']['range'] = "Range {$ltbug}-{$htbug}, hasil tbug = {$tbug}";

                // Status dalam atau luar range
                $dalamRentang = ($latestAntro->tinggi_badan >= $ltbug && $latestAntro->tinggi_badan <= $htbug);
                if ($dalamRentang) {
                    $interpretasiGizi['tbug']['status_range'] = "Tinggi anak masih dalam range PTG";
                } else {
                    if ($latestAntro->tinggi_badan < $ltbug) {
                        $selisihRange = $this->roundUp(($ltbug - $latestAntro->tinggi_badan), 1);
                        $interpretasiGizi['tbug']['status_range'] = "Tinggi anak {$selisihRange} cm dibawah range PTG";
                    } else {
                        $selisihRange = $this->roundUp(($latestAntro->tinggi_badan - $htbug), 1);
                        $interpretasiGizi['tbug']['status_range'] = "Tinggi anak {$selisihRange} cm diatas range PTG";
                    }
                }

                // Add actual height for comparison
                $interpretasiGizi['tbug']['aktual'] = $latestAntro->tinggi_badan;

                // Selisih between actual and potential height
                $interpretasiGizi['tbug']['selisih'] = $selisih;
                $interpretasiGizi['tbug']['selisih_absolut'] = $selisihAbs;
            }

            // E. Laju Pertumbuhan diambil dari data antro yang dipilih
            // Sort data berdasarkan tanggal periksa (ascending: terlama ke terbaru)
            usort($selectedPoints, function ($a, $b) {
                return strtotime($a['date']) - strtotime($b['date']);
            });

            // Ambil data antro untuk semua poin yang dipilih
            $antroData = [];
            foreach ($selectedPoints as $data) {
                $antro = DB::table('antro_patients')
                    ->where('id', $data['id'])
                    ->select('id', 'berat_badan', 'tinggi_badan', 'lingkar_kepala', 'tgl_periksa', 'usia_bulan', 'usia_hari', 'total_usia_hari')
                    ->first();

                $usiaBulanAntro = $this->roundUp($this->usiaBulanAntro($antro));

                // Hitung Nilai Z untuk laju pertumbuhan
                if ($antro->total_usia_hari <= 1856) {
                    $tableTbuAntro = DB::table('table2')
                        ->where('jenis_kelamin', $patient->jenis_kelamin)
                        ->where('day', $antro->total_usia_hari)
                        ->select('m', 'l', 's', 'sd0')
                        ->first();
                } elseif ($antro->total_usia_hari > 1856 && $antro->total_usia_hari <= 6935) {
                    if ($usiaBulanAntro == 60) {
                        $usiaBulanAntro += 1;
                    }

                    $tableTbuAntro = DB::table('table8')
                        ->where('jenis_kelamin', $patient->jenis_kelamin)
                        ->where('month', $usiaBulanAntro)
                        ->select('m', 'l', 's', 'sd0')
                        ->first();
                }

                $antro->z_score = $this->rumusA($antro->tinggi_badan, $tableTbuAntro->l, $tableTbuAntro->m, $tableTbuAntro->s);

                // Hitung TBUG tiap pasient jika ada tinggi badan orang tua
                if ($patient->tinggi_ayah && $patient->tinggi_ibu) {
                    $antro->tbug = $this->roundUp($tableTbuAntro->m * pow(1 + $tableTbuAntro->l * $tableTbuAntro->s * $zTpg, 1 / $tableTbuAntro->l), 1);
                } else {
                    $antro->tbug = null;
                }

                // dd($usiaBulanAntro);
                if ($antro) {
                    $antro->tgl_periksa_formatted = date('d/m/Y', strtotime($antro->tgl_periksa));
                    $antroData[] = $antro;
                }
            }

            // dd($antroData);
            // Initialize the laju pertumbuhan structure in interpretasiGizi
            $interpretasiGizi['laju_pertumbuhan'] = [
                'data_points' => [],    // Data points used for calculations
                'pertumbuhan' => [],    // Series of growth rate measurements
                'summary' => null       // Text summary if needed
            ];

            // Store the original antro data for reference
            $interpretasiGizi['laju_pertumbuhan']['data_points'] = array_map(function ($antro) {
                [$tahunUs, $bulanUs, $hariUs] = convertDaysToYear(
                    $antro->tgl_periksa,
                    $antro->total_usia_hari ?? 0,
                );
                $usiaSebenarnya = $tahunUs . ' th ' . $bulanUs . ' bl ' . $hariUs . ' hr';

                return [
                    'id' => $antro->id,
                    'tgl_periksa' => $antro->tgl_periksa,
                    'tgl_periksa_formatted' => $antro->tgl_periksa_formatted,
                    'usia_bulan' => $antro->usia_bulan,
                    'usia_hari' => $antro->usia_hari,
                    'total_usia_hari' => $antro->total_usia_hari,
                    'usia_sebenarnya' => $usiaSebenarnya,
                    'berat_badan' => $antro->berat_badan,
                    'tinggi_badan' => $antro->tinggi_badan,
                    'lingkar_kepala' => $antro->lingkar_kepala ?? null,
                    'z_score' => $antro->z_score,
                    'tbug' => $antro->tbug,
                ];
            }, $antroData);

            if (count($antroData) >= 2) {
                $pertumbuhanResults = [];

                for ($i = 0; $i < count($antroData) - 1; $i++) {
                    $awalData = $antroData[$i];
                    $akhirData = $antroData[$i + 1];

                    // Calculate age difference in days
                    $awalDataUsiaHari = $awalData->total_usia_hari;
                    $akhirDataUsiaHari = $akhirData->total_usia_hari;
                    $selisihHari = $akhirDataUsiaHari - $awalDataUsiaHari;

                    // Calculate age difference in months
                    $awalDataUsiaBulan = $awalData->usia_bulan + ($awalData->usia_hari / 30);
                    $akhirDataUsiaBulan = $akhirData->usia_bulan + ($akhirData->usia_hari / 30);
                    $hitungSelisihBulan = $this->roundUp($akhirDataUsiaBulan, 2) -
                        $this->roundUp($awalDataUsiaBulan, 2);
                    $selisihBulan = $this->roundUp($hitungSelisihBulan, 2);

                    // Calculate actual growth rate in cm/year
                    // LJ = (selisih TB / selisih hari ) x 365 (cm/thn)
                    if ($selisihHari > 0) {
                        $lajuPertumbuhan = $this->roundUp((($akhirData->tinggi_badan - $awalData->tinggi_badan) / $selisihHari) * 365, 1);
                    } else {
                        // Handle case where selisihHari is zero or negative
                        $lajuPertumbuhan = 0;
                    }

                    // Get reference table data for both time points
                    // If age is greater than 1856 days, use table8
                    // If age is less than or equal to 1856 days, use table2
                    if ($awalData->total_usia_hari > 1856) {
                        $awalUsiaBulan = $this->roundUp($awalDataUsiaBulan);
                        if ($awalUsiaBulan == 60) {
                            $awalUsiaBulan += 1;
                        }
                        $awalTable = DB::table('table8')
                            ->where('jenis_kelamin', $patient->jenis_kelamin)
                            ->where('month', $awalUsiaBulan)
                            ->select('m', 'l', 's')
                            ->first();
                    } else {
                        $awalTable = DB::table('table2')
                            ->where('jenis_kelamin', $patient->jenis_kelamin)
                            ->where('day', $awalData->total_usia_hari)
                            ->select('m', 'l', 's')
                            ->first();
                    }

                    if ($akhirData->total_usia_hari > 1856) {
                        $akhirUsiaBulan = $this->roundUp($akhirDataUsiaBulan);
                        if ($akhirUsiaBulan == 60) {
                            $akhirUsiaBulan += 1;
                        }
                        $akhirTable = DB::table('table8')
                            ->where('jenis_kelamin', $patient->jenis_kelamin)
                            ->where('month', $akhirUsiaBulan)
                            ->select('m', 'l', 's')
                            ->first();
                    } else {
                        $akhirTable = DB::table('table2')
                            ->where('jenis_kelamin', $patient->jenis_kelamin)
                            ->where('day', $akhirData->total_usia_hari)
                            ->select('m', 'l', 's')
                            ->first();
                    }

                    // Calculate normal height ranges for both time points
                    $awalHitung = $this->hitungTinggiNormalLajuPertumbuhan($awalTable, $awalData);
                    $akhirHitung = $this->hitungTinggiNormalLajuPertumbuhan($akhirTable, $akhirData);

                    // Calculate normal growth rate range
                    $lajuPertumbuhanBawah = $this->roundUp((($akhirHitung['tb_batas_normal']['bawah'] - $awalHitung['tb_batas_normal']['bawah']) / $selisihBulan) * 12, 1);
                    $lajuPertumbuhanAtas = $this->roundUp((($akhirHitung['tb_batas_normal']['atas'] - $awalHitung['tb_batas_normal']['atas']) / $selisihBulan) * 12, 1);

                    // Calculate genetic potential growth rate
                    // Laju Genetik
                    $genetikAwal = $this->hitungGenetik($awalData, $patient, $zTpg, $zTinggiPerkiraanBawah, $zTinggiPerkiraanAtas);
                    $genetikAkhir = $this->hitungGenetik($akhirData, $patient, $zTpg, $zTinggiPerkiraanBawah, $zTinggiPerkiraanAtas);

                    $genetikNow = $this->roundUp((($genetikAkhir['tbug'] - $genetikAwal['tbug']) / $selisihHari) * 365, 1);
                    $genetikBawah = $this->roundUp((($genetikAkhir['ltbug'] - $genetikAwal['ltbug']) / $selisihHari) * 365, 1);
                    $genetikAtas = $this->roundUp((($genetikAkhir['htbug'] - $genetikAwal['htbug']) / $selisihHari) * 365, 1);

                    // Mid Date
                    // Mid date = Tgl Periksa –(selisih hari /2)
                    $akhirDate = new \DateTime($akhirData->tgl_periksa);
                    $halfDays = floor($selisihHari / 2);
                    $midDate = (clone $akhirDate)->modify("-{$halfDays} days");
                    $midDateFormatted = $midDate->format('Y-m-d');
                    $midDateDisplay = $midDate->format('d/m/Y');

                    // Calculate usia mid date (child's age at mid-date)
                    $midDateUsiaHari = $akhirData->total_usia_hari - $halfDays;
                    [$midDateTahun, $midDateBulan, $midDateHari] = convertDaysToYear(
                        $midDateFormatted,
                        $midDateUsiaHari
                    );
                    $midDateUsiaFormatted = $midDateTahun . ' th ' . $midDateBulan . ' bl ' . $midDateHari . ' hr';

                    // Total age in months for easier comparison
                    $totalUsiaBulanMidDate = ($midDateTahun * 12) + $midDateBulan;

                    // Get normal growth rate based on mid date age
                    $normalLajuPertumbuhanMidDate = $this->hitungNormalLajuPertumbuhanMidDate($totalUsiaBulanMidDate, $patient->jenis_kelamin);

                    // Hitung Normal Laju Pertumbuhan
                    // N LJUG.   = ((TBUG 1- TBUG 2) / (TGL PERIKSA 1-TGL PERIKSA 2)) * 365 CM/THN
                    $normalLajuPertumbuhan = $this->hitungNormalLajuPertumbuhan($genetikAkhir['tbug'], $genetikAwal['tbug'], $akhirDataUsiaHari, $awalDataUsiaHari);
                    // dd($normalLajuPertumbuhan['nilai']);
                    // Hitung Delta Z
                    // Δ Z = (Z1-(Z2*0,95))/0,3123
                    $deltaZ = ($akhirData->z_score - ($awalData->z_score * 0.95)) / 0.3123;

                    // Build structured result for this measurement period
                    $periodResult = [
                        'label' => "P" . ($i + 1) . " – P" . ($i + 2),
                        'periode' => [
                            'awal' => [
                                'tanggal' => $awalData->tgl_periksa,
                                'tanggal_formatted' => $awalData->tgl_periksa_formatted,
                                'usia_bulan' => $awalData->usia_bulan,
                                'total_usia_hari' => $awalData->total_usia_hari,
                                'tinggi_badan' => $awalData->tinggi_badan
                            ],
                            'akhir' => [
                                'tanggal' => $akhirData->tgl_periksa,
                                'tanggal_formatted' => $akhirData->tgl_periksa_formatted,
                                'usia_bulan' => $awalData->usia_bulan,
                                'total_usia_hari' => $akhirData->total_usia_hari,
                                'tinggi_badan' => $akhirData->tinggi_badan
                            ],
                        ],
                        'mid_date' => [
                            'tanggal' => $midDateFormatted,
                            'tanggal_formatted' => $midDateDisplay,
                            'selisih_dari_akhir' => $halfDays,
                            'usia_hari' => $midDateUsiaHari,
                            'usia_formatted' => $midDateUsiaFormatted,
                            'usia_bulan_total' => $totalUsiaBulanMidDate,
                            'normal_laju_pertumbuhan' => $normalLajuPertumbuhanMidDate
                        ],
                        'selisih_bulan' => $selisihBulan,
                        'selisih_tinggi' => $this->roundUp($akhirData->tinggi_badan - $awalData->tinggi_badan, 1),
                        'aktual' => [
                            'nilai' => $lajuPertumbuhan,
                            'text' => "$lajuPertumbuhan cm/thn"
                        ],
                        'delta_z' => $deltaZ,
                        'delta_t' => $selisihHari,
                        'normal' => [
                            'nilai_normal' => $normalLajuPertumbuhan['nilai'],
                            'bawah' => $lajuPertumbuhanBawah,
                            'atas' => $lajuPertumbuhanAtas,
                            'text' => "$lajuPertumbuhanBawah - $lajuPertumbuhanAtas cm/thn"
                        ],
                        'genetik' => [
                            'nilai' => $genetikNow,
                            'bawah' => $genetikBawah,
                            'atas' => $genetikAtas,
                            'text' => "$genetikNow cm/thn ($genetikBawah - $genetikAtas cm/thn)"
                        ],
                        'status' => null // Will be filled below
                    ];

                    // Determine status based on growth rate
                    if ($lajuPertumbuhan < $lajuPertumbuhanBawah) {
                        $periodResult['status'] = 'Di bawah normal';
                    } elseif ($lajuPertumbuhan > $lajuPertumbuhanAtas) {
                        $periodResult['status'] = 'Di atas normal';
                    } else {
                        $periodResult['status'] = 'Normal';
                    }

                    $pertumbuhanResults[] = $periodResult;
                }

                // Add structured growth data to interpretasi gizi
                $interpretasiGizi['laju_pertumbuhan']['pertumbuhan'] = $pertumbuhanResults;

                // dd($interpretasiGizi);
                // Add summary if needed (overall assessment)
                if (count($pertumbuhanResults) > 0) {
                    $latestPeriod = end($pertumbuhanResults);
                    if ($latestPeriod['aktual']['nilai'] < $latestPeriod['normal']['bawah']) {
                        $interpretasiGizi['laju_pertumbuhan']['summary'] = "Laju pertumbuhan anak di bawah normal";
                    } elseif ($latestPeriod['aktual']['nilai'] > $latestPeriod['normal']['atas']) {
                        $interpretasiGizi['laju_pertumbuhan']['summary'] = "Laju pertumbuhan anak di atas normal";
                    } else {
                        $interpretasiGizi['laju_pertumbuhan']['summary'] = "Laju pertumbuhan anak normal";
                    }
                }
            }

            // dd($interpretasiGizi);

            // Return all the processed data for use in store, generatePdf, etc.
            return [
                'patient' => $patient,
                'latestAntro' => $latestAntro,
                'interpretasiGizi' => $interpretasiGizi,
                'selectedPoints' => $selectedPoints
            ];
        } catch (\Exception $e) {
            Log::error("Error processing assessment: " . $e->getMessage());
            throw $e; // Rethrow the exception to be handled by the caller
            return false;
        }
    }

    public function store(Request $request, $patientId)
    {
        session()->forget('from_submit');
        session()->forget('patient');
        session()->forget('latestAntro');
        session()->forget('interpretasiGizi');

        if (Auth::user()->roles()->first()->name !== 'super-admin') {
            // Get context for point system
            $context = getInstansiOrUserContext(Auth::user());
            $pointSetting = $this->pointService->findSettingByName('PENILAIAN');

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
                    ->with('error', 'Poin Anda tidak cukup atau masa aktif poin Anda sudah habis untuk melakukan penilaian! Silahkan top up poin terlebih dahulu.');
            }
        }

        // Validasi data yang diterima
        $selectedPoints = json_decode($request->input('selectedPoints'), true) ?? [];

        if (count($selectedPoints) === 0) {
            return back()->with('error', 'Belum ada data antro pasien yang dipilih!');
            // return response()->json([
            //     'status' => 'error',
            //     'message' => 'Belum ada data antro pasien yang dipilih!'
            // ], 400);
        }

        $validator = Validator::make(
            ['selectedPoints' => $selectedPoints],
            [
                'selectedPoints' => 'required|array|min:1',
                'selectedPoints.*.id' => 'required|integer|exists:antro_patients,id',
                'selectedPoints.*.date' => 'required|date',
            ]
        );

        if ($validator->fails()) {
            return back()->with('error', 'Terjadi kesalahan saat menghitung penilaian!');
            // return response()->json([
            //     'status' => 'error',
            //     'message' => 'Terjadi kesalahan saat menghitung penilaian!'
            // ], 400);
        }

        // Process assessment data
        $result = $this->prosesPenilaian($selectedPoints, $patientId);

        // Setelah berhasil menambahkan data, kurangi poin
        if (Auth::user()->roles()->first()->name !== 'super-admin') {
            if ($result) {
                $this->pointService->usage(
                    $context['user_id'],
                    $context['instansi_id'],
                    $pointSetting->points,
                    'Penilaian Pasien',
                    $pointSetting->id,
                    $patientId,
                );

                // Log penggunaan poin
                Log::info("Poin berhasil digunakan untuk penilaian pasien dengan ID: {$patientId}", [
                    'user_id' => $context['user_id'],
                    'instansi_id' => $context['instansi_id'],
                    'points' => $pointSetting->points,
                ]);
            }
        }

        if (!$result) {
            return back()->with('error', 'Terjadi kesalahan dalam memproses penilaian!');
            // return response()->json([
            //     'status' => 'error',
            //     'message' => 'Terjadi kesalahan dalam memproses penilaian!'
            // ], 500);
        }

        // Store data in session for subsequent requests
        session()->put('from_submit', true);
        session()->put('patient', $result['patient']);
        session()->put('latestAntro', $result['latestAntro']);
        session()->put('interpretasiGizi', $result['interpretasiGizi']);

        // return response()->json([
        //     'status' => 'success',
        //     'message' => "Penilaian berhasil dihitung!",
        //     'redirect' => route('patient.penilaian.index')
        // ], 200);
        if (Auth::user()->roles()->first()->name === 'super-admin') {
            return redirect()->route('super-admin.patient.penilaian.index');
        }

        if ($request->has('skip_confirmation') && $request->skip_confirmation) {
            Cookie::queue('skip_confirm', 'true', 60 * 24 * 30); // 30 hari
        }
        return redirect()->route('patient.penilaian.index');
    }

    public function index()
    {
        $fromSubmit = session('from_submit');
        $patient = session('patient');
        $interpretasiGizi = session('interpretasiGizi');
        $latestAntro = session('latestAntro');

        if (!$fromSubmit || !$patient || !$interpretasiGizi || !$latestAntro) {
            return redirect()->route('patient.index')->with('error', 'Silahkan lakukan penilaian terlebih dahulu!');
        }

        if (Auth::user()->roles()->first()->name === 'super-admin') {
            return view('super-admin.pasien.penilaian.index', [
                'fromSubmit' => $fromSubmit,
                'patient' => $patient,
                'latestAntro' => $latestAntro,
                'interpretasiGizi' => $interpretasiGizi,
            ]);
        }
        return view('admin.pasien.penilaian.index', [
            'fromSubmit' => $fromSubmit,
            'patient' => $patient,
            'latestAntro' => $latestAntro,
            'interpretasiGizi' => $interpretasiGizi,
        ]);
    }
}
