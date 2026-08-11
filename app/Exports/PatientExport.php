<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PatientExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    use Exportable;

    private $rowNumber = 0;

    public function query()
    {
        return DB::table('patients')
            ->leftJoin('users', 'patients.created_by', '=', 'users.id')
            ->leftJoin('instansis', 'users.instansi_id', '=', 'instansis.id')
            ->select(
                'patients.*',
                'users.name as created_by_name',
            )
            ->groupBy(
                'patients.id',
                'patients.created_by',
                'patients.kode_lokal',
                'patients.nama',
                'patients.jenis_kelamin',
                'patients.tgl_lahir',
                'patients.usia_kehamilan_minggu',
                'patients.count_usia_kehamilan_minggu',
                'patients.no_wa',
                'patients.email',
                'patients.tinggi_ayah',
                'patients.tinggi_ibu',
                'patients.created_at',
                'patients.updated_at',
                'users.name'
            )
            ->orderBy('patients.created_by')
            ->orderBy('patients.created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'No',
            'Created By',
            'Kode Lokal',
            'Nama Pasien',
            'Jenis Kelamin',
            'Tanggal Lahir',
            'Usia Kehamilan (minggu)',
            'No Whatsapp',
            'Email',
            'Tinggi Ayah (cm)',
            'Tinggi Ibu (cm)',
        ];
    }

    public function map($patient): array
    {
        // Reset row number when exporting
        $this->rowNumber++;

        // Format tanggal lahir
        $tanggalLahir = $patient->tgl_lahir ? Carbon::parse($patient->tgl_lahir)->format('d-m-Y') : '';
        $jenisKelamin = $patient->jenis_kelamin == 'L' ? 'Laki-laki' : ($patient->jenis_kelamin == 'P' ? 'Perempuan' : '');

        return [
            $this->rowNumber,
            $patient->created_by_name,
            $patient->kode_lokal,
            $patient->nama,
            $jenisKelamin,
            $tanggalLahir,
            $patient->usia_kehamilan_minggu,
            $patient->no_wa,
            $patient->email,
            $patient->tinggi_ayah,
            $patient->tinggi_ibu,
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
