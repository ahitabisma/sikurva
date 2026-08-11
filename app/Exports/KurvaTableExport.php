<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class KurvaTableExport implements FromCollection, WithHeadings, WithEvents
{
    protected $namaTabel;
    protected $column;

    public function __construct($namaTabel, $column)
    {
        $this->namaTabel = $namaTabel;
        $this->column = $column;
    }

    public function headings(): array
    {
        $baseHeadings = [
            'jenis_kelamin',
            $this->column, // day, length, atau month
            'l',
            'm',
            's',
            'sd4neg',
            'sd3neg',
            'sd2neg',
            'sd1neg',
            'sd0',
            'sd1',
            'sd2',
            'sd3',
            'sd4',
        ];

        $baseHeadingsIg = [
            'jenis_kelamin',
            $this->column, // length untuk table 12, week untuk table 9, 10, 11
        ];

        if ($this->namaTabel === 'table8') {
            $baseHeadings[] = 'stdev';
            $baseHeadings[] = 'sd5neg';
            return $baseHeadings;
        } elseif (in_array($this->namaTabel, ['table9', 'table10', 'table11'])) {
            // Add 'days' right after the column name (week)
            $baseHeadingsIg[] = 'days';
            // Then add the z-score columns
            $baseHeadingsIg = array_merge($baseHeadingsIg, [
                'z3neg',
                'z2neg',
                'z1neg',
                'z0',
                'z1',
                'z2',
                'z3',
            ]);
            return $baseHeadingsIg;
        } elseif ($this->namaTabel === 'table12') {
            $baseHeadingsIg = array_merge($baseHeadingsIg, [
                'z3neg',
                'z2neg',
                'z1neg',
                'z0',
                'z1',
                'z2',
                'z3',
            ]);
            return $baseHeadingsIg;
        } else {
            return $baseHeadings;
        }
    }

    public function collection()
    {
        if ($this->namaTabel === 'table8') {
            return new Collection([
                ['L', 0, 0.121212, 0.121212, 0.121212, 0.121212, 0.121212, 0.121212, 0.121212, 0.121212, 0.121212, 0.121212, 0.121212, 0.121212, 0.141414, 0.151515],
                ['P', 1, 0.131313, 0.131313, 0.131313, 0.131313, 0.131313, 0.131313, 0.131313, 0.131313, 0.131313, 0.131313, 0.131313, 0.131313, 0.141414, 0.151515],
            ]);
        } elseif (in_array($this->namaTabel, ['table9', 'table10', 'table11'])) {
            // Reorder the data to match the new heading order
            // [jenis_kelamin, week, days, z3neg, z2neg, z1neg, z0, z1, z2, z3]
            return new Collection([
                ['L', 0, 0, 0.121212, 0.121212, 0.121212, 0.121212, 0.121212, 0.121212, 0.121212],
                ['P', 1, 7, 0.131313, 0.131313, 0.131313, 0.131313, 0.131313, 0.131313, 0.131313],
            ]);
        } elseif ($this->namaTabel === 'table12') {
            return new Collection([
                ['L', 45.0, 0.121212, 0.121212, 0.121212, 0.121212, 0.121212, 0.121212, 0.121212],
                ['P', 46.5, 0.131313, 0.131313, 0.131313, 0.131313, 0.131313, 0.131313, 0.131313],
            ]);
        } else {
            return new Collection([
                ['L', 0, 0.121212, 0.121212, 0.121212, 0.121212, 0.121212, 0.121212, 0.121212, 0.121212, 0.121212, 0.121212, 0.121212, 0.121212],
                ['P', 1, 0.131313, 0.131313, 0.131313, 0.131313, 0.131313, 0.131313, 0.131313, 0.131313, 0.131313, 0.131313, 0.131313, 0.131313],
            ]);
        }
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Determine highest column based on table type
                if ($this->namaTabel === 'table8') {
                    $highestColumn = 'P'; // 16 columns
                } elseif (in_array($this->namaTabel, ['table9', 'table10', 'table11'])) {
                    $highestColumn = 'J'; // 10 columns (jenis_kelamin, week, days, z3neg to z3)
                } elseif ($this->namaTabel === 'table12') {
                    $highestColumn = 'I'; // 9 columns (jenis_kelamin, length, z3neg to z3)
                } else {
                    $highestColumn = 'N'; // 14 columns
                }

                // Tulis heading di baris 1
                $headingRow = 1;
                $headings = $this->headings();
                foreach ($headings as $index => $heading) {
                    $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 1);
                    $sheet->setCellValue("{$column}{$headingRow}", $heading);
                }
                $sheet->getStyle("A{$headingRow}:{$highestColumn}{$headingRow}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFCCCCCC'], // Abu-abu
                    ],
                    'font' => ['bold' => true],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);

                // Tulis data contoh mulai dari baris 2
                $dataStartRow = $headingRow + 1;
                $data = $this->collection();
                foreach ($data as $rowIndex => $rowData) {
                    $currentRow = $dataStartRow + $rowIndex;
                    foreach ($rowData as $colIndex => $value) {
                        $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
                        $sheet->setCellValue("{$column}{$currentRow}", $value);
                    }
                }
                $highestRow = $dataStartRow + count($data) - 1;

                // Tambahkan dropdown untuk kolom jenis_kelamin (kolom A)
                $validation = $sheet->getDataValidation("A{$dataStartRow}:A{$highestRow}");
                $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
                $validation->setAllowBlank(false);
                $validation->setShowDropDown(true);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setErrorTitle('Input error');
                $validation->setError('Value must be either L or P');
                $validation->setPromptTitle('Pick from list');
                $validation->setPrompt('Please select a value from the dropdown');
                $validation->setFormula1('"L,P"');

                // Tambahkan border untuk data
                $sheet->getStyle("A{$dataStartRow}:{$highestColumn}{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);
            },
        ];
    }
}
