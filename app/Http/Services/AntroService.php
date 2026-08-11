<?php

namespace App\Http\Services;

use App\Http\Repositories\AntroRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class AntroService
{
    protected $antroRepository;
    public function __construct(AntroRepository $antroRepository)
    {
        $this->antroRepository = $antroRepository;
    }

    public function getAll()
    {
        return $this->antroRepository->all();
    }

    public function getAllByPatientIdPaginated($patientId, $page = 25)
    {
        return $this->antroRepository->allByPatientIdPaginated($patientId, $page);
    }

    public function getById($id)
    {
        try {
            return $this->antroRepository->find($id);
        } catch (ModelNotFoundException $e) {
            Log::error("AntroService not found: " . $e->getMessage());
            throw new ModelNotFoundException("Data not found");
        }
    }

    public function create(array $data)
    {
        return $this->antroRepository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->antroRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->antroRepository->delete($id);
    }

    // Process Chart data
    // Helper untuk process chart data agar tidak process di bladenya
    public function processChartData($patient, $dataAntro)
    {
        $chartData = [
            'dataTable1' => collect(),
            'dataTable2' => collect(),
            'dataTable3' => collect(),
            'dataTable4' => collect(),
            'dataTable5' => collect(),
            'dataTable6' => collect(),
            'dataTable7' => collect(),
            'dataTable8' => collect(),
            'dataTable9' => collect(),
            'dataTable10' => collect(),
            'dataTable11' => collect(),
            'dataTable12' => collect(),
        ];

        if ($patient->usia_kehamilan_minggu >= 37 && $patient->usia_kehamilan_minggu <= 40) {
            $chartData['dataTable1'] = $this->processFullTermTable1($dataAntro);
            $chartData['dataTable2'] = $this->processFullTermTable2($dataAntro);
            $chartData['dataTable3'] = $this->processFullTermTable3($dataAntro);
            $chartData['dataTable4'] = $this->processFullTermTable4($dataAntro);
            $chartData['dataTable5'] = $this->processFullTermTable5($dataAntro);
            $chartData['dataTable6'] = $this->processFullTermTable6($dataAntro);
            $chartData['dataTable7'] = $this->processFullTermTable7($dataAntro);
            $chartData['dataTable8'] = $this->processFullTermTable8($dataAntro);
        } else {
            // Premature processing
            $chartData['dataTable9'] = $this->processPrematureTable9($dataAntro);
            $chartData['dataTable10'] = $this->processPrematureTable10($dataAntro);
            $chartData['dataTable11'] = $this->processPrematureTable11($dataAntro);
            $chartData['dataTable12'] = $this->processPrematureTable12($dataAntro);

            // Always process these tables for all user types
            $chartData['dataTable1'] = $this->processPrematureTable1($dataAntro);
            $chartData['dataTable2'] = $this->processPrematureTable2($dataAntro);
            $chartData['dataTable3'] = $this->processPrematureTable3($dataAntro);
            $chartData['dataTable4'] = $this->processPrematureTable4($dataAntro);
            $chartData['dataTable5'] = $this->processPrematureTable5($dataAntro);
            $chartData['dataTable6'] = $this->processPrematureTable6($dataAntro);
            $chartData['dataTable7'] = $this->processPrematureTable7($dataAntro);
            $chartData['dataTable8'] = $this->processPrematureTable8($dataAntro);
        }

        return $chartData;
    }

    // Then implement all the individual processing methods like:
    private function processFullTermTable1($dataAntro)
    {
        // Data Table 1 (Berat Badan)
        return $dataAntro
            ->filter(function ($antro) {
                // Jika usia kehamilan >= 37 minggu dan <= 40 minggu filter berdasarkan usia_bulan <= 60 dan berat_badan
                return $antro->usia_bulan <= 60 && $antro->berat_badan;
            })
            ->map(function ($antro) {
                // Konversi usia_bulan ke hari (1 bulan = 30 hari) dan tambahkan usia_hari
                $totalDays = $antro->usia_bulan * 30 + $antro->usia_hari;
                return ['key' => $totalDays, 'value' => $antro->berat_badan];
            })
            ->pluck('value', 'key');
    }
    private function processFullTermTable2($dataAntro)
    {
        // Data Table 2 (Tinggi Badan)
        return $dataAntro
            ->filter(function ($antro) {
                // Jika usia kehamilan >= 37 minggu dan <= 40 minggu filter berdasarkan usia_bulan <= 60 dan berat_badan
                return $antro->usia_bulan <= 60 && $antro->tinggi_badan;
            })
            ->map(function ($antro) {
                // Konversi usia_bulan ke hari (1 bulan = 30 hari) dan tambahkan usia_hari
                $totalDays = $antro->usia_bulan * 30 + $antro->usia_hari;
                return ['key' => $totalDays, 'value' => $antro->tinggi_badan];
            })
            ->pluck('value', 'key');
    }
    private function processFullTermTable3($dataAntro)
    {
        // Data Table 3 (Lingkar Kepala)
        return $dataAntro
            ->filter(function ($antro) {
                // Jika usia kehamilan >= 37 minggu dan <= 40 minggu filter berdasarkan usia_bulan <= 60 dan berat_badan
                return $antro->usia_bulan <= 60 && $antro->lingkar_kepala;
            })
            ->map(function ($antro) {
                // Konversi usia_bulan ke hari (1 bulan = 30 hari) dan tambahkan usia_hari
                $totalDays = $antro->usia_bulan * 30 + $antro->usia_hari;
                return ['key' => $totalDays, 'value' => $antro->lingkar_kepala];
            })
            ->pluck('value', 'key');
    }
    private function processFullTermTable4($dataAntro)
    {
        // Data Table 4 (Berat Badan => Tinggi Badan)
        return $dataAntro
            ->filter(function ($antro) {
                // Jika usia kehamilan >= 37 minggu dan <= 40 minggu
                return $antro->usia_bulan <= 60 && $antro->berat_badan && $antro->tinggi_badan;
            })
            ->map(function ($antro) {
                return [
                    'tinggi_badan' => $antro->tinggi_badan,
                    'berat_badan' => $antro->berat_badan,
                ];
            });
    }
    private function processFullTermTable5($dataAntro)
    {
        // Data Table 5 (IMT => Usia Hari)
        return $dataAntro
            ->filter(function ($antro) {
                // Jika usia kehamilan >= 37 minggu dan <= 40 minggu
                return $antro->usia_bulan <= 60 && $antro->imt;
            })
            ->map(function ($antro) {
                // Konversi usia_bulan ke hari (1 bulan = 30 hari) dan tambahkan usia_hari
                $totalDays = $antro->usia_bulan * 30 + $antro->usia_hari;
                return ['key' => $totalDays, 'value' => $antro->imt];
            })
            ->pluck('value', 'key');
    }
    private function processFullTermTable6($dataAntro)
    {
        // Data Table 6 (Usia Hari => Berat Badan )
        return $dataAntro
            ->filter(function ($antro) {
                // Jika usia kehamilan >= 37 minggu dan <= 40 minggu
                return $antro->usia_bulan > 60 && $antro->usia_bulan <= 120 && $antro->berat_badan;
            })
            ->map(function ($antro) {
                // Konversi usia_bulan ke hari (1 bulan = 30 hari) dan tambahkan usia_hari
                $totalDays = $antro->usia_bulan;
                return ['key' => $totalDays, 'value' => $antro->berat_badan];
            })
            ->pluck('value', 'key');
    }
    private function processFullTermTable7($dataAntro)
    {
        // Data Table 7 (IMT => Usia Hari)
        return $dataAntro
            ->filter(function ($antro) {
                // Jika usia kehamilan >= 37 minggu dan <= 40 minggu
                return $antro->usia_bulan > 60 && $antro->imt;
            })
            ->map(function ($antro) {
                // Konversi usia_bulan ke hari (1 bulan = 30 hari) dan tambahkan usia_hari
                $totalDays = $antro->usia_bulan;
                return ['key' => $totalDays, 'value' => $antro->imt];
            })
            ->pluck('value', 'key');
    }
    private function processFullTermTable8($dataAntro)
    {
        // Data Table 8 (Usia Hari => Tinggi Badan )
        return $dataAntro
            ->filter(function ($antro) {
                if ($antro->usia_bulan > 60) {
                    // Jika usia kehamilan >= 37 minggu dan <= 40 minggu
                    return $antro->usia_bulan && $antro->tinggi_badan;
                }
            })
            ->map(function ($antro) {
                // Konversi usia_bulan ke hari (1 bulan = 30 hari) dan tambahkan usia_hari
                return ['key' => $antro->usia_bulan, 'value' => $antro->tinggi_badan];
            })
            ->pluck('value', 'key');
    }

    private function processPrematureTable1($dataAntro)
    {
        // Jika US 6-24 bln && US > 24 bln
        // US = Tgl Px – Tgl Lahir
        // Data Table 1 (Berat Badan)
        return $dataAntro
            ->filter(function ($antro) {
                // Jika usia kronologis / US <= 24 bln dan PMA > 64 mg
                if ($antro->total_usia_hari <= 730 && $antro->usia_gestasi_minggu && $antro->usia_gestasi_minggu > 64 && $antro->berat_badan) {
                    return $antro->usia_koreksi_total_hari && $antro->berat_badan;
                }
                // Bila US 6-24 bulan Gunakan Kurva WHO dengan UK (hr)
                // elseif ($antro->total_usia_hari > 120 && $antro->total_usia_hari <= 730) {
                //     // Filter berdasarkan usia_bulan <= 60 dan berat_badan
                //     return $antro->usia_koreksi_total_hari && $antro->berat_badan;
                // }
                // Jika > 24 bulan
                // UK = US
                // Gunakan Kurva WHO dengan UK) sumbu X adalah US (hr)
                elseif ($antro->total_usia_hari > 730 && $antro->total_usia_hari <= 1825) {
                    return $antro->total_usia_hari && $antro->berat_badan;
                } else {
                    return;
                }
            })
            ->map(function ($antro) {
                // Jika usia kronologis / US <= 24 bln dan PMA > 64 mg
                if ($antro->total_usia_hari <= 730 && $antro->usia_gestasi_minggu && $antro->usia_gestasi_minggu > 64 && $antro->berat_badan) {
                    return ['key' => $antro->usia_koreksi_total_hari, 'value' => $antro->berat_badan];
                }
                // Bila US 6-24 bulan Gunakan Kurva WHO dengan UK (hr)
                // elseif ($antro->total_usia_hari > 120 && $antro->total_usia_hari <= 730) {
                //     // Filter berdasarkan usia_bulan <= 60 dan berat_badan
                //     return ['key' => $antro->usia_koreksi_total_hari, 'value' => $antro->berat_badan];
                // }
                // Jika > 24 bulan
                // UK = US
                // Gunakan Kurva WHO dengan UK) sumbu X adalah US (hr)
                elseif ($antro->total_usia_hari > 730 && $antro->total_usia_hari <= 1825) {
                    return ['key' => $antro->total_usia_hari, 'value' => $antro->berat_badan];
                } else {
                    return;
                }
            })
            ->filter()
            ->pluck('value', 'key');
    }
    private function processPrematureTable2($dataAntro)
    {
        // Jika US 6-24 bln && US > 24 bln
        // US = Tgl Px – Tgl Lahir
        // Data Table 2 (Tinggi Badan)
        return $dataAntro
            ->filter(function ($antro) {
                // Jika usia kronologis / US <= 24 bln dan PMA > 64 mg
                if ($antro->total_usia_hari <= 730 && $antro->usia_gestasi_minggu && $antro->usia_gestasi_minggu > 64 && $antro->tinggi_badan) {
                    return $antro->usia_koreksi_total_hari && $antro->tinggi_badan;
                }
                // Bila US 6-24 bulan Gunakan Kurva WHO dengan UK (hr)
                // elseif ($antro->total_usia_hari > 120 && $antro->total_usia_hari <= 730) {
                //     // Filter berdasarkan usia_bulan <= 60 dan tinggi_badan
                //     return $antro->usia_koreksi_total_hari && $antro->tinggi_badan;
                // }
                // Jika > 24 bulan
                // UK = US
                // Gunakan Kurva WHO dengan UK) sumbu X adalah US (hr)
                elseif ($antro->total_usia_hari > 730 && $antro->total_usia_hari <= 1825) {
                    return $antro->total_usia_hari && $antro->tinggi_badan;
                } else {
                    return;
                }
            })
            ->map(function ($antro) {
                // Jika usia kronologis / US <= 24 bln dan PMA > 64 mg
                if ($antro->total_usia_hari <= 730 && $antro->usia_gestasi_minggu && $antro->usia_gestasi_minggu > 64 && $antro->tinggi_badan) {
                    return ['key' => $antro->usia_koreksi_total_hari, 'value' => $antro->tinggi_badan];
                } else if ($antro->total_usia_hari > 730 && $antro->total_usia_hari <= 1825) {
                    return ['key' => $antro->total_usia_hari, 'value' => $antro->tinggi_badan];
                } else {
                    return;
                }

                // Usia > 24 bulan: pakai total usia
                // return ['key' => $antro->total_usia_hari, 'value' => $antro->tinggi_badan];
            })
            ->pluck('value', 'key');
    }
    private function processPrematureTable3($dataAntro)
    {
        // Jika US 6-24 bln && US > 24 bln
        // US = Tgl Px – Tgl Lahir
        // Data Table 3 (Lingkar Kepala)
        return $dataAntro
            ->filter(function ($antro) {
                // Jika usia kronologis / US <= 24 bln dan PMA > 64 mg
                if ($antro->total_usia_hari <= 730 && $antro->usia_gestasi_minggu && $antro->usia_gestasi_minggu > 64 && $antro->lingkar_kepala) {
                    return $antro->usia_koreksi_total_hari && $antro->lingkar_kepala;
                }
                // Bila US 6-24 bulan Gunakan Kurva WHO dengan UK (hr)
                // elseif ($antro->total_usia_hari > 120 && $antro->total_usia_hari <= 730) {
                //     // Filter berdasarkan usia_bulan <= 60 dan lingkar_kepala
                //     return $antro->usia_koreksi_total_hari && $antro->lingkar_kepala;
                // }
                // // Jika > 24 bulan
                // // UK = US
                // // Gunakan Kurva WHO dengan UK) sumbu X adalah US (hr)
                elseif ($antro->total_usia_hari > 730 && $antro->total_usia_hari <= 1825) {
                    return $antro->total_usia_hari && $antro->lingkar_kepala;
                } else {
                    return;
                }
            })
            ->map(function ($antro) {
                // Jika usia kronologis / US <= 24 bln dan PMA > 64 mg
                if ($antro->total_usia_hari <= 730 && $antro->usia_gestasi_minggu && $antro->usia_gestasi_minggu > 64 && $antro->lingkar_kepala) {
                    return ['key' => $antro->usia_koreksi_total_hari, 'value' => $antro->lingkar_kepala];
                } else if ($antro->total_usia_hari > 730 && $antro->total_usia_hari <= 1825) {
                    return ['key' => $antro->total_usia_hari, 'value' => $antro->lingkar_kepala];
                } else {
                    return;
                }

                // Usia > 24 bulan: pakai total usia
                // return ['key' => $antro->total_usia_hari, 'value' => $antro->lingkar_kepala];
            })
            ->pluck('value', 'key');
    }
    private function processPrematureTable4($dataAntro)
    {
        // Jika US 6-24 bln && US > 24 bln
        // US = Tgl Px – Tgl Lahir
        // Data Table 4 (Berat Badan => Tinggi Badan)
        return $dataAntro
            ->filter(function ($antro) {
                // Jika usia kronologis / US <= 24 bln dan PMA > 64 mg
                if ($antro->total_usia_hari <= 730 && $antro->usia_gestasi_minggu && $antro->usia_gestasi_minggu > 64 && $antro->tinggi_badan && $antro->berat_badan) {
                    return $antro->tinggi_badan && $antro->berat_badan;
                }
                // Bila US 6-24 bulan Gunakan Kurva WHO dengan UK (hr)
                // elseif ($antro->total_usia_hari > 120 && $antro->total_usia_hari <= 730) {
                //     // Filter berdasarkan usia_bulan <= 60 dan berat_badan
                //     return $antro->tinggi_badan && $antro->berat_badan;
                // }
                // Jika > 24 bulan
                // UK = US
                // Gunakan Kurva WHO dengan UK) sumbu X adalah US (hr)
                elseif ($antro->total_usia_hari > 730 && $antro->total_usia_hari <= 1825) {
                    return $antro->tinggi_badan && $antro->berat_badan;
                } else {
                    return;
                }
            })
            ->map(function ($antro) {
                return [
                    'tinggi_badan' => $antro->tinggi_badan,
                    'berat_badan' => $antro->berat_badan,
                ];
            });
    }
    private function processPrematureTable5($dataAntro)
    {
        // Jika US 6-24 bln && US > 24 bln
        // US = Tgl Px – Tgl Lahir
        // Data Table 5 (IMT => Usia Hari)
        return $dataAntro
            ->filter(function ($antro) {
                // Jika usia kronologis / US <= 24 bln dan PMA > 64 mg
                if ($antro->total_usia_hari <= 730 && $antro->usia_gestasi_minggu && $antro->usia_gestasi_minggu > 64 && $antro->imt) {
                    return $antro->usia_koreksi_total_hari && $antro->imt;
                }
                // // Bila US 6-24 bulan Gunakan Kurva WHO dengan UK (hr)
                // elseif ($antro->total_usia_hari > 120 && $antro->total_usia_hari <= 730) {
                //     // Filter berdasarkan usia_bulan <= 60 dan imt
                //     return $antro->usia_koreksi_total_hari && $antro->imt;
                // }
                // // Jika > 24 bulan
                // // UK = US
                // // Gunakan Kurva WHO dengan UK) sumbu X adalah US (hr)
                elseif ($antro->total_usia_hari > 730 && $antro->total_usia_hari <= 1825) {
                    return $antro->total_usia_hari && $antro->imt;
                } else {
                    return;
                }
            })
            ->map(function ($antro) {
                // Jika usia kronologis / US <= 24 bln dan PMA > 64 mg
                if ($antro->total_usia_hari <= 730 && $antro->usia_gestasi_minggu && $antro->usia_gestasi_minggu > 64 && $antro->imt) {
                    return ['key' => $antro->usia_koreksi_total_hari, 'value' => $antro->imt];
                } else if ($antro->total_usia_hari > 730 && $antro->total_usia_hari <= 1825) {
                    return ['key' => $antro->total_usia_hari, 'value' => $antro->imt];
                } else {
                    return;
                }
                // return ['key' => $antro->usia_koreksi_total_hari, 'value' => $antro->imt];

                // Usia > 24 bulan: pakai total usia
                // return ['key' => $antro->total_usia_hari, 'value' => $antro->imt];
            })
            ->pluck('value', 'key');
    }
    private function processPrematureTable6($dataAntro)
    {
        // Jika US 6-24 bln && US > 24 bln
        // US = Tgl Px – Tgl Lahir
        // Data Table 6 (Usia Hari => Berat Badan )
        return $dataAntro
            ->filter(function ($antro) {
                if ($antro->total_usia_hari > 1825 && $antro->total_usia_hari <= 3650) {
                    return $antro->usia_bulan && $antro->berat_badan;
                }
            })
            ->map(function ($antro) {
                return ['key' => $antro->usia_bulan, 'value' => $antro->berat_badan];
            })
            ->pluck('value', 'key');
    }
    private function processPrematureTable7($dataAntro)
    {
        // Jika US 6-24 bln && US > 24 bln
        // US = Tgl Px – Tgl Lahir
        // Data Table 7 (IMT => Usia Hari)
        return $dataAntro
            ->filter(function ($antro) {
                if ($antro->total_usia_hari > 1825) {
                    return $antro->usia_bulan && $antro->imt;
                }
            })
            ->map(function ($antro) {
                return ['key' => $antro->usia_bulan, 'value' => $antro->imt];
            })
            ->pluck('value', 'key');
    }
    private function processPrematureTable8($dataAntro)
    {
        // Jika US 6-24 bln && US > 24 bln
        // US = Tgl Px – Tgl Lahir
        // Data Table 8 (Usia Hari => Tinggi Badan )
        return $dataAntro
            ->filter(function ($antro) {
                if ($antro->total_usia_hari > 1825) {
                    return $antro->usia_bulan && $antro->tinggi_badan;
                }
            })
            ->map(function ($antro) {
                return ['key' => $antro->usia_bulan, 'value' => $antro->tinggi_badan];
            })
            ->pluck('value', 'key');
    }
    private function processPrematureTable9($dataAntro)
    {
        // Grafik Intergrowth hanya tampil ketika user adalah Nakes
        // Jika US <= 6 bln
        // Data Table 9 (Usia Gestasi => Berat Badan )
        return $dataAntro
            ->filter(function ($antro) {
                // Bila US <=6 bulan
                if ($antro->total_usia_hari <= 730 && $antro->berat_badan) {
                    if ($antro->usia_gestasi_minggu && $antro->usia_gestasi_minggu < 64) {
                        return $antro->usia_gestasi_total_hari && $antro->berat_badan;
                    }
                }
            })
            ->map(function ($antro) {
                if ($antro->total_usia_hari <= 730 && $antro->berat_badan) {
                    if ($antro->usia_gestasi_minggu && $antro->usia_gestasi_minggu < 64) {
                        return ['key' => $antro->usia_gestasi_total_hari, 'value' => $antro->berat_badan];
                    }
                }
            })
            ->pluck('value', 'key');
    }
    private function processPrematureTable10($dataAntro)
    {
        // Jika US <= 6 bln
        // Data Table 10 (Usia Gestasi => Tinggi Badan )
        return $dataAntro
            ->filter(function ($antro) {
                // Bila US <=6 bulan
                if ($antro->total_usia_hari <= 730 && $antro->tinggi_badan) {
                    if ($antro->usia_gestasi_minggu && $antro->usia_gestasi_minggu < 64) {
                        return $antro->usia_gestasi_total_hari && $antro->tinggi_badan;
                    }
                }
            })
            ->map(function ($antro) {
                if ($antro->total_usia_hari <= 730 && $antro->tinggi_badan) {
                    if ($antro->usia_gestasi_minggu && $antro->usia_gestasi_minggu < 64) {
                        return ['key' => $antro->usia_gestasi_total_hari, 'value' => $antro->tinggi_badan];
                    }
                }
            })
            ->pluck('value', 'key');
    }
    private function processPrematureTable11($dataAntro)
    {
        // Jika US <= 6 bln
        // Data Table 11 (Usia Gestasi => Lingkar Kepala )
        return $dataAntro
            ->filter(function ($antro) {
                // Bila US <=6 bulan
                if ($antro->total_usia_hari <= 730 && $antro->lingkar_kepala) {
                    if ($antro->usia_gestasi_minggu && $antro->usia_gestasi_minggu < 64) {
                        return $antro->usia_gestasi_total_hari && $antro->lingkar_kepala;
                    }
                }
            })
            ->map(function ($antro) {
                if ($antro->total_usia_hari <= 730 && $antro->lingkar_kepala) {
                    if ($antro->usia_gestasi_minggu && $antro->usia_gestasi_minggu < 64) {
                        return ['key' => $antro->usia_gestasi_total_hari, 'value' => $antro->lingkar_kepala];
                    }
                }
            })
            ->pluck('value', 'key');
    }
    private function processPrematureTable12($dataAntro)
    {
        // Jika US <= 6 bln
        // Data Table 12 (Panjang Badan => Berat Badan )
        return $dataAntro
            ->filter(function ($antro) {
                // Bila US <=6 bulan
                if (
                    ($antro->total_usia_hari <= 730 && $antro->berat_badan && $antro->tinggi_badan)
                ) {
                    if ($antro->usia_gestasi_minggu && $antro->usia_gestasi_minggu < 64) {
                        return $antro->berat_badan && $antro->tinggi_badan;
                    }
                }
            })
            ->map(function ($antro) {
                if (
                    ($antro->total_usia_hari <= 730 && $antro->berat_badan && $antro->tinggi_badan)
                ) {
                    if ($antro->usia_gestasi_minggu && $antro->usia_gestasi_minggu < 64) {
                        return ['key' => $antro->tinggi_badan, 'value' => $antro->berat_badan];
                    }
                }
            })
            ->pluck('value', 'key');
    }
}
