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
        Schema::create('kurva_table_settings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tabel', 100)->unique();
            $table->string('nama')->unique();
            $table->text('judul');
            $table->string('ket_y');
            $table->decimal('y_min', 10, 2)->default(0);
            $table->decimal('y_max', 10, 2)->default(0);
            $table->decimal('y_mayor', 10, 2)->default(0);
            $table->decimal('y_minor', 10, 2)->default(0);
            $table->string('y_unit',);
            $table->string('ket_x');
            $table->decimal('x_min', 10, 2)->default(0);
            $table->decimal('x_max', 10, 2)->default(0);
            $table->decimal('x_mayor', 10, 2)->default(0);
            $table->decimal('x_minor', 10, 2)->default(0);
            $table->string('x_unit',);
            $table->string('sumbu_y');
            $table->string('sumbu_x');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_settings');
    }
};
