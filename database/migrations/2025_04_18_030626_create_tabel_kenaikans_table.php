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
        Schema::create('tabel_kenaikans', function (Blueprint $table) {
            $table->id();
            $table->integer('usia_bulan');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->integer('bb_bawah');
            $table->integer('bb_atas');
            $table->string('bb_unit');
            $table->decimal('tb_bawah', 2, 1);
            $table->decimal('tb_atas', 2, 1);
            $table->string('tb_unit');
            $table->decimal('lk_bawah', 2, 1)->nullable();
            $table->decimal('lk_atas', 2, 1)->nullable();
            $table->string('lk_unit')->nullable();
            $table->timestamps();

            $table->unique(['usia_bulan', 'jenis_kelamin'], 'table_kenaikans_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabel_kenaikans');
    }
};
