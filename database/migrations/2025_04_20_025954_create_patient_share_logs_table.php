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
        Schema::create('patient_share_logs', function (Blueprint $table) {
            $table->id();

            // User yang melakukan aksi
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // User yang menjadi target (jika applicable, misalnya saat sharing ke user lain)
            $table->foreignId('target_user_id')->nullable()->constrained('users')->nullOnDelete();

            // Pasien yang terkait
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();

            // Opsional: antro pasien jika log berkaitan dengan data antro
            $table->foreignId('antro_patient_id')->nullable()->constrained('antro_patients')->nullOnDelete();

            // Aksi yang dilakukan: create, update, delete, share, unshare, accept, reject, dll
            $table->string('action');

            // Konteks aksi: patient, antro, share, collaborator
            $table->string('context');

            // Penjelasan singkat bebas
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('non_nakes_patient_share_logs');
    }
};
