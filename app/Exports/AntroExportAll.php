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

class AntroExportAll implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    use Exportable;

    private $rowNumber = 0;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return DB::table('antro_patients')
            ->leftJoin('patients', 'antro_patients.patient_id', '=', 'patients.id')
            ->leftJoin('users', 'antro_patients.created_by', '=', 'users.id')
            ->orderBy('patients.nama', 'asc')
            ->orderBy('antro_patients.tgl_periksa', 'desc')
            ->select(
                'patients.id as patient_id',
                'patients.nama as nama_pasien',
                'patients.jenis_kelamin',
                'patients.tgl_lahir',
                'antro_patients.id as antro_id',
                'antro_patients.tgl_periksa',
                'antro_patients.usia_bulan',
                'antro_patients.usia_hari',
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
            'Jenis Kelamin',
            'Tanggal Lahir',
            'Tanggal Periksa',
            'Usia (bulan)',
            'Usia (hari)',
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

        // Format jenis kelamin
        $jenisKelamin = $antro->jenis_kelamin == 'L' ? 'Laki-laki' : ($antro->jenis_kelamin == 'P' ? 'Perempuan' : '');

        return [
            $this->rowNumber,
            $antro->nama_pasien,
            $jenisKelamin,
            $tglLahir,
            $tglPeriksa,
            $antro->usia_bulan,
            $antro->usia_hari,
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
