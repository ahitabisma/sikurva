<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AntroExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    use Exportable;

    protected $patientId;
    private $rowNumber = 0;

    public function __construct($patientId)
    {
        $this->patientId = $patientId;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return DB::table('antro_patients')
            ->leftJoin('patients', 'antro_patients.patient_id', '=', 'patients.id')
            ->leftJoin('users', 'antro_patients.created_by', '=', 'users.id')
            ->where('antro_patients.patient_id', $this->patientId)
            ->orderBy('antro_patients.tgl_periksa', 'desc')
            ->select(
                'patients.nama as nama_pasien',
                'patients.tgl_lahir',
                'antro_patients.tgl_periksa',
                'antro_patients.berat_badan as bb',
                'antro_patients.tinggi_badan as tb',
                'antro_patients.lingkar_kepala as lk',
                'users.name as created_by'
            )
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Pasien',
            'Tanggal Lahir',
            'Tanggal Periksa',
            'BB (kg)',
            'TB (cm)',
            'LK (cm)',
            'Created By'
        ];
    }

    public function map($antro): array
    {
        // Increment row number
        $this->rowNumber++;

        // Format dates
        $tglLahir = $antro->tgl_lahir ? Carbon::parse($antro->tgl_lahir)->format('d-m-Y') : '';
        $tglPeriksa = $antro->tgl_periksa ? Carbon::parse($antro->tgl_periksa)->format('d-m-Y') : '';

        return [
            $this->rowNumber,
            $antro->nama_pasien,
            $tglLahir,
            $tglPeriksa,
            $antro->bb,
            $antro->tb,
            $antro->lk,
            $antro->created_by
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the header row
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
