<?php

namespace Database\Seeders;

use App\Imports\AntroImport;
use App\Imports\PatientsImport;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $namaTabel = 'pasien';
        $filePath = "template_table/template_{$namaTabel}.xlsx";
        $userAdmin = User::where('email', 'admin@gmail.com')->first();

        if (Storage::disk('private')->exists($filePath)) {
            try {
                $this->command->info("Starting import for {$namaTabel}");

                // Buat instance import dan jalankan dengan output untuk progress bar
                $import = new PatientsImport($userAdmin->id);
                $import->withOutput($this->command->getOutput())->import($filePath, 'private');

                $this->command->info("Import successful for {$namaTabel}");
            } catch (\Exception $e) {
                $this->command->error("Failed to import {$namaTabel}: " . $e->getMessage());
            }
        } else {
            $this->command->warn("Template file for {$namaTabel} not found at: {$filePath}");
        }

        // Import data antro untuk pasien Audrey (ID 1)
        $this->importAntroData('template_antro_audrey.xlsx', 1, 'Audrey');

        // Import data antro untuk pasien Claysen (ID 2)
        $this->importAntroData('template_antro_claysen.xlsx', 2, 'Claysen');
    }

    private function importAntroData($filename, $patientId, $patientName): void
    {
        $filePath = "template_table/{$filename}";

        if (Storage::disk('private')->exists($filePath)) {
            try {
                $this->command->info("Starting import antro data for patient: {$patientName}");

                // Buat instance import dengan patient_id
                $import = new AntroImport($patientId);
                $import->withOutput($this->command->getOutput())->import($filePath, 'private');

                $this->command->info("Import antro data successful for patient: {$patientName}");
            } catch (\Exception $e) {
                $this->command->error("Failed to import antro data for {$patientName}: " . $e->getMessage());
            }
        } else {
            $this->command->warn("Template file for antro data not found at: {$filePath}");
        }
    }
}
