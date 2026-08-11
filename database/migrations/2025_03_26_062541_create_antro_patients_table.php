<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('antro_patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->date('tgl_periksa')->default(now());
            $table->integer('usia_bulan'); // Usia dalam bulan (0 - 228 bulan)
            $table->integer('usia_hari'); // Usia dalam hari (0 - 228 hari)
            $table->integer('total_usia_hari'); // Total Usia Hari
            $table->decimal('berat_badan', 5, 2)->nullable(); // Maksimum 175.00 kg
            $table->decimal('tinggi_badan', 5, 1)->nullable(); // Maksimum 205.0 cm
            $table->decimal('lingkar_kepala', 4, 1)->nullable(); // Maksimum 56.0 cm, nullable
            $table->decimal('imt', 5, 1)->nullable(); // IMT
            $table->integer('usia_koreksi_bulan')->nullable()->default(0);
            $table->integer('usia_koreksi_total_hari')->nullable()->default(0);
            $table->integer('usia_gestasi_minggu')->nullable()->default(0);
            $table->integer('usia_gestasi_total_hari')->nullable()->default(0);
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antro_patients');
    }
};
