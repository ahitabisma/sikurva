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

class AntroTemplateExport implements FromCollection, WithHeadings, WithEvents
{
    public function headings(): array
    {
        return [];
    }

    public function collection()
    {
        return new Collection([]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestColumn = 'D'; // Kolom terakhir adalah E (5 kolom)

                // 1. Tambahkan keterangan di baris 1-7 dengan warna kuning
                $sheet->setCellValue('A1', 'Keterangan Pengisian:');
                $sheet->getStyle('A1')->getFont()->setBold(true);
                $keterangan = [
                    '• Kolom yang wajib diisi: tgl_periksa, berat_badan (opsional jika tinggi badan tidak kosong), tinggi_badan (opsional jika berat badan tidak kosong), lingkar_kepala (opsional).',
                    '• Tgl Periksa: Diisi dengan format DD-MM-YY atau DD/MM/YY atau DD-MM-YYYY atau DD/MM/YYYY. Tidak boleh kosong.',
                    '• Bayi Kurang Bulan (Usia Kehamilan <37 minggu) dan Usia <24 bulan:',
                    '  - Berat Badan: 0,40-12,00 kg (gunakan koma untuk desimal, 2 digit di belakang koma)',
                    '  - Tinggi Badan: 26,0-76,0 cm (gunakan koma untuk desimal, 1 digit di belakang koma)',
                    '  - Lingkar Kepala: 19,0-48,0 cm (gunakan koma untuk desimal, 1 digit di belakang koma)',
                    '• Usia <= 60 bulan: Berat Badan (1,70-30,00 kg), Tinggi Badan (42,5-125,0 cm), Lingkar Kepala (30,0-56,0 cm).',
                    '• Usia 61-120 bulan: Berat Badan (11,50-67,50 kg), Tinggi Badan (92,5-205,0 cm), Lingkar Kepala (kosongkan).',
                    '• Usia 121-228 bulan: Berat Badan (18,50-175,00 kg), Tinggi Badan (92,5-205,0 cm), Lingkar Kepala (kosongkan).',
                    '• Usia dihitung otomatis berdasarkan tgl_periksa dan tgl_lahir pasien.',
                    '• Batasan untuk melakukan import adalah 25 data. Jika lebih dari itu, silakan lakukan import secara bertahap.',
                    '• Jika terjadi kegagalan, coba periksa baris yang bermasalah dan pastikan semua kolom wajib terisi dengan benar.',
                ];
                foreach ($keterangan as $index => $text) {
                    $row = 2 + $index;
                    $sheet->setCellValue("A{$row}", $text);
                    $sheet->getStyle("A{$row}:{$highestColumn}{$row}")->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['argb' => 'FFFFFF00'], // Kuning
                        ],
                    ]);
                }

                // 2. Sisipkan baris kosong hingga baris 11 untuk heading
                $headingRow = 15;
                $sheet->insertNewRowBefore($headingRow, $headingRow - $sheet->getHighestRow());

                // 3. Tulis heading di baris 11
                $headings = [
                    'tgl_periksa',
                    'berat_badan',
                    'tinggi_badan',
                    'lingkar_kepala',
                ];

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

                // 4. Tulis data contoh mulai dari baris 9
                $dataStartRow = $headingRow + 1;
                $data = new Collection([
                    [
                        '01/10/2025', // tgl_periksa
                        '20', // berat_badan (usia <= 60 bulan)
                        '110.0', // tinggi_badan (usia <= 60 bulan)
                        null, // lingkar_kepala (usia <= 60 bulan)
                    ],
                    [
                        '26-07-2023',
                        '25', // berat_badan (usia 61-120 bulan)
                        '110.5', // tinggi_badan (usia 61-228 bulan)
                        null, // lingkar_kepala (usia > 60 bulan)
                    ],
                    [
                        '12-12-2024',
                        '27.00', // berat_badan (usia 121-228 bulan)
                        '110.0', // tinggi_badan (usia 61-228 bulan)
                        null, // lingkar_kepala (usia > 60 bulan)
                    ],
                ]);
                foreach ($data as $rowIndex => $rowData) {
                    $currentRow = $dataStartRow + $rowIndex;
                    foreach ($rowData as $colIndex => $value) {
                        $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
                        $sheet->setCellValue("{$column}{$currentRow}", $value);
                    }
                }
                $highestRow = $dataStartRow + count($data) - 1;
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
