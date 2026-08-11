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

class PatientTemplateExport implements FromCollection, WithHeadings, WithEvents
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
                $highestColumn = 'I'; // Kolom terakhir sekarang 'H' karena 'kode_mr' dihapus

                // 1. Tambahkan keterangan di baris 1-11 dengan warna kuning
                $sheet->setCellValue('A1', 'Keterangan Pengisian:');
                $sheet->getStyle('A1')->getFont()->setBold(true);
                $keterangan = [
                    '• Kolom yang wajib diisi adalah nama, tgl_lahir, jenis_kelamin, dan usia_kehamilan_minggu (Usia Gestasi).',
                    '• Kode MR: Diisi dengan angka, huruf atau gabungan dari angka dan huruf atau biarkan kosong.',
                    '• Nama: Diisi nama pasien. Tidak boleh kosong.',
                    '• Jenis Kelamin: Hanya boleh diisi "L" (Laki-laki) atau "P" (Perempuan). Tidak boleh kosong.',
                    '• Tgl Lahir: Diisi dengan format DD-MM-YY atau DD/MM/YY atau DD-MM-YYYY atau DD/MM/YYYY. Tidak boleh kosong.',
                    '• Usia Kehamilan Minggu (Usia Gestasi (minggu)): Diisi dengan angka 27-40 atau biarkan kosong.',
                    '• Tinggi Ayah (cm): Diisi dengan angka atau biarkan kosong.',
                    '• Tinggi Ibu (cm): Diisi dengan angka atau biarkan kosong.',
                    '• No WA: Diisi tanpa angka 0 di depan. Langsung mulai dari angka 62, misalnya jika nomor Anda adalah 081234567890, maka isikan sebagai 6281234567890.',
                    '• Email: Diisi dengan format email yang benar, misalnya: pasien@gmail.com.',
                    '• Batasan untuk melakukan import adalah 50 pasien. Jika lebih dari itu, silakan lakukan import secara bertahap.',
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

                // 2. Sisipkan baris kosong hingga baris 8 untuk heading
                $headingRow = 15;
                $sheet->insertNewRowBefore($headingRow, $headingRow - $sheet->getHighestRow());

                // 3. Tulis heading di baris 8 (tanpa kode_lokal)
                $headings = [
                    'kode_mr',
                    'nama',
                    'jenis_kelamin',
                    'tgl_lahir',
                    'usia_kehamilan_minggu',
                    'tinggi_ayah',
                    'tinggi_ibu',
                    'no_wa',
                    'email',
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

                // 4. Tulis data contoh mulai dari baris 9 (tanpa kode_lokal)
                $dataStartRow = $headingRow + 1;
                $data = new Collection([
                    [
                        '3301',
                        'Budi',
                        'L',
                        '15/05/2019',
                        '36',
                        '170',
                        '165',
                        '6281234567890',
                        'budi@example.com',
                    ],
                    [
                        'ASD301',
                        'Ani',
                        'P',
                        '15/05/2021',
                        36,
                        '175',
                        '160',
                        '6281345678901',
                        'ani@example.com',
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

                // 5. Dropdown untuk jenis_kelamin (kolom B, karena A sekarang nama)
                $validationJenisKelamin = $sheet->getCell("C{$dataStartRow}")->getDataValidation();
                $validationJenisKelamin->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $validationJenisKelamin->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
                $validationJenisKelamin->setAllowBlank(false);
                $validationJenisKelamin->setShowDropDown(true);
                $validationJenisKelamin->setFormula1('"L,P"');
                $sheet->setDataValidation("C{$dataStartRow}:C66", $validationJenisKelamin);
            },
        ];
    }
}
