<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TableDataExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    use Exportable;

    protected $tableName;
    protected $columnName;
    private $rowNumber = 0;

    public function __construct($tableName, $columnName = null)
    {
        $this->tableName = $tableName;
        $this->columnName = $columnName;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = DB::table($this->tableName);

        // Order by gender first (L first, then P), then by columnName if provided
        $query->orderBy('jenis_kelamin', 'asc'); // 'L' comes before 'P' alphabetically

        if ($this->columnName) {
            $query->orderBy($this->columnName);
        }

        return $query->get();
    }

    public function headings(): array
    {
        $headings = ['No', 'Jenis Kelamin'];

        // Add column name if provided
        if ($this->columnName) {
            $headings[] = ucfirst($this->columnName);
        }

        // Tables 9-12 have different column structure
        if (in_array($this->tableName, ['table9', 'table10', 'table11', 'table12'])) {
            if (in_array($this->tableName, ['table9', 'table10', 'table11'])) {
                $headings[] = 'Day';
            }
            $headings = array_merge($headings, ['Z (-3)', 'Z (-2)', 'Z (-1)', 'Z (0)', 'Z (1)', 'Z (2)', 'Z (3)']);
        } else {
            $headings = array_merge($headings, [
                'L',
                'M',
                'S',
                'SD4neg',
                'SD3neg',
                'SD2neg',
                'SD1neg',
                'SD0',
                'SD1',
                'SD2',
                'SD3',
                'SD4'
            ]);

            // Table8 has additional columns
            if ($this->tableName === 'table8') {
                $headings = array_merge($headings, ['StDev', 'SD5Neg']);
            }
        }

        return $headings;
    }

    public function map($row): array
    {
        $this->rowNumber++;

        $mappedRow = [
            $this->rowNumber,
            $row->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
        ];

        // Add the column value if provided
        if ($this->columnName) {
            $columnName = $this->columnName;
            $mappedRow[] = $row->$columnName;
        }

        // Tables 9-12 have different column structure
        if (in_array($this->tableName, ['table9', 'table10', 'table11', 'table12'])) {
            if (in_array($this->tableName, ['table9', 'table10', 'table11'])) {
                $mappedRow[] = $row->days;
            }
            $mappedRow = array_merge($mappedRow, [
                $row->z3neg,
                $row->z2neg,
                $row->z1neg,
                $row->z0,
                $row->z1,
                $row->z2,
                $row->z3
            ]);
        } else {
            $mappedRow = array_merge($mappedRow, [
                $row->l,
                $row->m,
                $row->s,
                $row->sd4neg,
                $row->sd3neg,
                $row->sd2neg,
                $row->sd1neg,
                $row->sd0,
                $row->sd1,
                $row->sd2,
                $row->sd3,
                $row->sd4
            ]);

            // Table8 has additional columns
            if ($this->tableName === 'table8') {
                $mappedRow = array_merge($mappedRow, [
                    $row->stdev,
                    $row->sd5neg
                ]);
            }
        }

        return $mappedRow;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row (heading)
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '5B9BD5'], // Light blue color
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],
        ];
    }

    public function title(): string
    {
        $tableTitle = ucfirst(str_replace('table', 'Table ', $this->tableName));

        if ($this->columnName) {
            $tableTitle .= ' - ' . ucfirst($this->columnName);
        }

        return $tableTitle;
    }
}
