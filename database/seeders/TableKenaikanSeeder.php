<?php

namespace Database\Seeders;

use App\Models\TabelKenaikan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class TableKenaikanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $path = "template_table/template_table_kenaikan.csv";

        if (!Storage::disk('private')->exists($path)) {
            $this->command->error("CSV file not found at: $path");
            return;
        }

        $fullPath = Storage::disk('private')->path($path);

        if (($handle = fopen($fullPath, 'r')) !== false) {

            $header = fgetcsv($handle); // skip header

            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                TabelKenaikan::create([
                    'usia_bulan' => (int) $row[0],
                    'jenis_kelamin' => $row[1],
                    'bb_bawah' => (int) $row[2],
                    'bb_atas' => (int) $row[3],
                    'bb_unit' => $row[4],
                    'tb_bawah' => (float) $row[5],
                    'tb_atas' => (float) $row[6],
                    'tb_unit' => $row[7],
                    'lk_bawah' => (float) $row[8] ?? null,
                    'lk_atas' => (float) $row[9] ?? null,
                    'lk_unit' => $row[10] ?? null,
                ]);
            }

            fclose($handle);
        }
    }
}
