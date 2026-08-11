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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('kode_lokal', 50);
            $table->string('nama');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tgl_lahir');
            $table->integer('usia_kehamilan_minggu')->default(40);
            $table->integer('count_usia_kehamilan_minggu')->default(40);
            $table->integer('tinggi_ayah')->nullable(); // Integer, nullable
            $table->integer('tinggi_ibu')->nullable(); // Integer, nullable
            $table->string('no_wa', 15)->nullable();
            $table->string('email')->nullable();
            $table->timestamps();

            $table->unique(['created_by', 'kode_lokal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
