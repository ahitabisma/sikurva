<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Illuminate\Database\Eloquent\Model;

class KurvaTableImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading, WithProgressBar
{
    use Importable;
    protected $namaTabel;
    protected $column;

    private $modelTable = [
        'table1' => 'App\Models\Tabel1',
        'table2' => 'App\Models\Tabel2',
        'table3' => 'App\Models\Tabel3',
        'table4' => 'App\Models\Tabel4',
        'table5' => 'App\Models\Tabel5',
        'table6' => 'App\Models\Tabel6',
        'table7' => 'App\Models\Tabel7',
        'table8' => 'App\Models\Tabel8',
        'table9' => 'App\Models\Tabel9',
        'table10' => 'App\Models\Tabel10',
        'table11' => 'App\Models\Tabel11',
        'table12' => 'App\Models\Tabel12',
    ];

    public function __construct($namaTabel, $column)
    {
        $this->namaTabel = $namaTabel;
        $this->column = $column;
    }

    public function model(array $row)
    {
        // Skip baris yang sepenuhnya kosong
        if (empty(array_filter($row))) {
            return null;
        }

        $modelClass = $this->modelTable[$this->namaTabel];

        // Base data untuk tabel standar (1-7)
        $data = [
            'jenis_kelamin' => $row['jenis_kelamin'],
            $this->column => $row[$this->column],
        ];

        if (in_array($this->namaTabel, ['table9', 'table10', 'table11'])) {
            // Struktur untuk tabel 9, 10, 11
            $data['days'] = $row['days'];
            $data['z3neg'] = $row['z3neg'];
            $data['z2neg'] = $row['z2neg'];
            $data['z1neg'] = $row['z1neg'];
            $data['z0'] = $row['z0'];
            $data['z1'] = $row['z1'];
            $data['z2'] = $row['z2'];
            $data['z3'] = $row['z3'];
        } elseif ($this->namaTabel === 'table12') {
            // Struktur untuk tabel 12
            $data['z3neg'] = $row['z3neg'];
            $data['z2neg'] = $row['z2neg'];
            $data['z1neg'] = $row['z1neg'];
            $data['z0'] = $row['z0'];
            $data['z1'] = $row['z1'];
            $data['z2'] = $row['z2'];
            $data['z3'] = $row['z3'];
        } elseif ($this->namaTabel === 'table8') {
            // Struktur untuk tabel 8
            $data['l'] = $row['l'];
            $data['m'] = $row['m'];
            $data['s'] = $row['s'];
            $data['sd4neg'] = $row['sd4neg'];
            $data['sd3neg'] = $row['sd3neg'];
            $data['sd2neg'] = $row['sd2neg'];
            $data['sd1neg'] = $row['sd1neg'];
            $data['sd0'] = $row['sd0'];
            $data['sd1'] = $row['sd1'];
            $data['sd2'] = $row['sd2'];
            $data['sd3'] = $row['sd3'];
            $data['sd4'] = $row['sd4'];
            $data['stdev'] = $row['stdev'];
            $data['sd5neg'] = $row['sd5neg'];
        } else {
            // Struktur untuk tabel 1-7
            $data['l'] = $row['l'];
            $data['m'] = $row['m'];
            $data['s'] = $row['s'];
            $data['sd4neg'] = $row['sd4neg'];
            $data['sd3neg'] = $row['sd3neg'];
            $data['sd2neg'] = $row['sd2neg'];
            $data['sd1neg'] = $row['sd1neg'];
            $data['sd0'] = $row['sd0'];
            $data['sd1'] = $row['sd1'];
            $data['sd2'] = $row['sd2'];
            $data['sd3'] = $row['sd3'];
            $data['sd4'] = $row['sd4'];
        }

        // Menggunakan Model untuk batch insert
        return new $modelClass($data);
    }

    public function headingRow(): int
    {
        return 1; // Heading ada di baris pertama
    }

    public function rules(): array
    {
        $baseRules = [
            'jenis_kelamin' => 'required|in:L,P',
            $this->column => 'required|numeric',
        ];

        if (in_array($this->namaTabel, ['table9', 'table10', 'table11'])) {
            // Rules untuk tabel 9, 10, 11
            $baseRules['days'] = 'required|numeric';
            $baseRules['z3neg'] = 'required|numeric';
            $baseRules['z2neg'] = 'required|numeric';
            $baseRules['z1neg'] = 'required|numeric';
            $baseRules['z0'] = 'required|numeric';
            $baseRules['z1'] = 'required|numeric';
            $baseRules['z2'] = 'required|numeric';
            $baseRules['z3'] = 'required|numeric';
        } elseif ($this->namaTabel === 'table12') {
            // Rules untuk tabel 12
            $baseRules['z3neg'] = 'required|numeric';
            $baseRules['z2neg'] = 'required|numeric';
            $baseRules['z1neg'] = 'required|numeric';
            $baseRules['z0'] = 'required|numeric';
            $baseRules['z1'] = 'required|numeric';
            $baseRules['z2'] = 'required|numeric';
            $baseRules['z3'] = 'required|numeric';
        } elseif ($this->namaTabel === 'table8') {
            // Rules untuk tabel 8
            $baseRules['l'] = 'required|numeric';
            $baseRules['m'] = 'required|numeric';
            $baseRules['s'] = 'required|numeric';
            $baseRules['sd4neg'] = 'required|numeric';
            $baseRules['sd3neg'] = 'required|numeric';
            $baseRules['sd2neg'] = 'required|numeric';
            $baseRules['sd1neg'] = 'required|numeric';
            $baseRules['sd0'] = 'required|numeric';
            $baseRules['sd1'] = 'required|numeric';
            $baseRules['sd2'] = 'required|numeric';
            $baseRules['sd3'] = 'required|numeric';
            $baseRules['sd4'] = 'required|numeric';
            $baseRules['stdev'] = 'required|numeric';
            $baseRules['sd5neg'] = 'required|numeric';
        } else {
            // Rules untuk tabel 1-7
            $baseRules['l'] = 'required|numeric';
            $baseRules['m'] = 'required|numeric';
            $baseRules['s'] = 'required|numeric';
            $baseRules['sd4neg'] = 'required|numeric';
            $baseRules['sd3neg'] = 'required|numeric';
            $baseRules['sd2neg'] = 'required|numeric';
            $baseRules['sd1neg'] = 'required|numeric';
            $baseRules['sd0'] = 'required|numeric';
            $baseRules['sd1'] = 'required|numeric';
            $baseRules['sd2'] = 'required|numeric';
            $baseRules['sd3'] = 'required|numeric';
            $baseRules['sd4'] = 'required|numeric';
        }

        return $baseRules;
    }

    public function customValidationMessages()
    {
        $baseMessages = [
            'jenis_kelamin.required' => 'Jenis kelamin wajib diisi.',
            'jenis_kelamin.in' => 'Jenis kelamin harus L atau P.',
            "{$this->column}.required" => ucfirst($this->column) . ' wajib diisi.',
            "{$this->column}.numeric" => ucfirst($this->column) . ' harus berupa angka.',
        ];

        if (in_array($this->namaTabel, ['table9', 'table10', 'table11', 'table12'])) {
            // Messages untuk tabel 9, 10, 11, 12
            $zMessages = [
                'days.required' => 'Days wajib diisi.',
                'days.numeric' => 'Days harus berupa angka.',
                'z3neg.required' => 'Z-3 wajib diisi.',
                'z3neg.numeric' => 'Z-3 harus berupa angka.',
                'z2neg.required' => 'Z-2 wajib diisi.',
                'z2neg.numeric' => 'Z-2 harus berupa angka.',
                'z1neg.required' => 'Z-1 wajib diisi.',
                'z1neg.numeric' => 'Z-1 harus berupa angka.',
                'z0.required' => 'Z0 wajib diisi.',
                'z0.numeric' => 'Z0 harus berupa angka.',
                'z1.required' => 'Z1 wajib diisi.',
                'z1.numeric' => 'Z1 harus berupa angka.',
                'z2.required' => 'Z2 wajib diisi.',
                'z2.numeric' => 'Z2 harus berupa angka.',
                'z3.required' => 'Z3 wajib diisi.',
                'z3.numeric' => 'Z3 harus berupa angka.',
            ];

            // Only add days message for tables 9, 10, 11
            if (!in_array($this->namaTabel, ['table12'])) {
                $baseMessages = array_merge($baseMessages, $zMessages);
            } else {
                // Remove days from messages for table 12
                unset($zMessages['days.required'], $zMessages['days.numeric']);
                $baseMessages = array_merge($baseMessages, $zMessages);
            }
        } elseif ($this->namaTabel === 'table8') {
            // Messages untuk tabel 8
            $baseMessages = array_merge($baseMessages, [
                'l.required' => 'L wajib diisi.',
                'l.numeric' => 'L harus berupa angka.',
                'm.required' => 'M wajib diisi.',
                'm.numeric' => 'M harus berupa angka.',
                's.required' => 'S wajib diisi.',
                's.numeric' => 'S harus berupa angka.',
                'sd4neg.required' => 'SD4Neg wajib diisi.',
                'sd4neg.numeric' => 'SD4Neg harus berupa angka.',
                'sd3neg.required' => 'SD3Neg wajib diisi.',
                'sd3neg.numeric' => 'SD3Neg harus berupa angka.',
                'sd2neg.required' => 'SD2Neg wajib diisi.',
                'sd2neg.numeric' => 'SD2Neg harus berupa angka.',
                'sd1neg.required' => 'SD1Neg wajib diisi.',
                'sd1neg.numeric' => 'SD1Neg harus berupa angka.',
                'sd0.required' => 'SD0 wajib diisi.',
                'sd0.numeric' => 'SD0 harus berupa angka.',
                'sd1.required' => 'SD1 wajib diisi.',
                'sd1.numeric' => 'SD1 harus berupa angka.',
                'sd2.required' => 'SD2 wajib diisi.',
                'sd2.numeric' => 'SD2 harus berupa angka.',
                'sd3.required' => 'SD3 wajib diisi.',
                'sd3.numeric' => 'SD3 harus berupa angka.',
                'sd4.required' => 'SD4 wajib diisi.',
                'sd4.numeric' => 'SD4 harus berupa angka.',
                'stdev.required' => 'Stdev wajib diisi.',
                'stdev.numeric' => 'Stdev harus berupa angka.',
                'sd5neg.required' => 'SD5Neg wajib diisi.',
                'sd5neg.numeric' => 'SD5Neg harus berupa angka.',
            ]);
        } else {
            // Messages untuk tabel 1-7
            $baseMessages = array_merge($baseMessages, [
                'l.required' => 'L wajib diisi.',
                'l.numeric' => 'L harus berupa angka.',
                'm.required' => 'M wajib diisi.',
                'm.numeric' => 'M harus berupa angka.',
                's.required' => 'S wajib diisi.',
                's.numeric' => 'S harus berupa angka.',
                'sd4neg.required' => 'SD4Neg wajib diisi.',
                'sd4neg.numeric' => 'SD4Neg harus berupa angka.',
                'sd3neg.required' => 'SD3Neg wajib diisi.',
                'sd3neg.numeric' => 'SD3Neg harus berupa angka.',
                'sd2neg.required' => 'SD2Neg wajib diisi.',
                'sd2neg.numeric' => 'SD2Neg harus berupa angka.',
                'sd1neg.required' => 'SD1Neg wajib diisi.',
                'sd1neg.numeric' => 'SD1Neg harus berupa angka.',
                'sd0.required' => 'SD0 wajib diisi.',
                'sd0.numeric' => 'SD0 harus berupa angka.',
                'sd1.required' => 'SD1 wajib diisi.',
                'sd1.numeric' => 'SD1 harus berupa angka.',
                'sd2.required' => 'SD2 wajib diisi.',
                'sd2.numeric' => 'SD2 harus berupa angka.',
                'sd3.required' => 'SD3 wajib diisi.',
                'sd3.numeric' => 'SD3 harus berupa angka.',
                'sd4.required' => 'SD4 wajib diisi.',
                'sd4.numeric' => 'SD4 harus berupa angka.',
            ]);
        }

        return $baseMessages;
    }

    public function batchSize(): int
    {
        return 200; // Jumlah record per batch insert
    }

    public function chunkSize(): int
    {
        return 200; // Jumlah record per chunk reading
    }
}
