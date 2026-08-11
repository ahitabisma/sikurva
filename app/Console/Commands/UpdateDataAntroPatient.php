<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateDataAntroPatient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-antro';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for update data antro patient because there is change in database structure';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $antros = app(\App\Http\Services\AntroService::class)->getAll(); // Sesuaikan dengan method kamu

        foreach ($antros as $antro) {
            // $patient = app(\App\Http\Services\PatientService::class)->findById($antro->patient_id);
            // if (!$patient) continue;

            // Bangun fake request object
            $request = new \Illuminate\Http\Request([
                'tgl_periksa' => $antro->tgl_periksa,
                'berat_badan' => $antro->berat_badan,
                'tinggi_badan' => $antro->tinggi_badan,
                'lingkar_kepala' => $antro->lingkar_kepala,
                'created_by' => $antro->created_by,
            ]);

            try {
                app(\App\Http\Controllers\Admin\AntroController::class)->update($request, $antro->id);
                $this->info("Updated antro ID: {$antro->id}");
            } catch (\Exception $e) {
                $this->error("Gagal update antro ID: {$antro->id} - " . $e->getMessage());
            }
        }

        $this->info("Selesai update semua data antro.");
    }
}
