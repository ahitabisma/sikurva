<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UserExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    use Exportable;

    private $rowNumber = 0;

    public function query()
    {
        return DB::table('users')
            ->leftJoin('instansis', 'users.instansi_id', '=', 'instansis.id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.address',
                'users.phone',
                'users.instansi_id',
                'users.is_nakes',
                'instansis.name as instansi_name'
            )
            ->groupBy(
                'users.id',
                'users.name',
                'users.email',
                'users.address',
                'users.phone',
                'users.instansi_id',
                'users.is_nakes',
                'instansis.name'
            )
            ->orderBy('users.instansi_id', 'desc');
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Email',
            'Alamat',
            'No HP',
            'Status',
            'Nama Instansi',
        ];
    }

    public function map($user): array
    {
        // Increment row number
        $this->rowNumber++;

        // Format status
        $status = $user->is_nakes ? 'Nakes' : 'Awam';

        return [
            $this->rowNumber,
            $user->name,
            $user->email,
            $user->address,
            $user->phone,
            $status,
            $user->instansi_name ?? '-',
        ];
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
}
