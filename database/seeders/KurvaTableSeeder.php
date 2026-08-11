<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\KurvaTableImport;
use App\Models\KurvaTableSetting;
use Illuminate\Support\Facades\Storage;

class KurvaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Daftar tabel yang akan di-seed
        $tables = array_merge(KurvaTableSetting::TABLE_COLUMNS, KurvaTableSetting::TABLE_COLUMNS_IG);

        foreach ($tables as $namaTabel => $column) {
            $filePath = "template_table/template_{$namaTabel}.xlsx";

            if (Storage::disk('private')->exists($filePath)) {
                try {
                    $this->command->info("Starting import for {$namaTabel}");

                    // Buat instance import dan jalankan dengan output untuk progress bar
                    $import = new KurvaTableImport($namaTabel, $column);
                    $import->withOutput($this->command->getOutput())->import($filePath, 'private');

                    $this->command->info("Import successful for {$namaTabel}");
                } catch (\Exception $e) {
                    $this->command->error("Failed to import {$namaTabel}: " . $e->getMessage());
                }
            } else {
                $this->command->warn("Template file for {$namaTabel} not found at: {$filePath}");
            }
        }
    }
}
