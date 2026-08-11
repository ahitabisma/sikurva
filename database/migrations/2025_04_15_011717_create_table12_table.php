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
        Schema::create('table12', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->decimal('length', 5, 3);
            $table->decimal('z3neg', 5, 3);
            $table->decimal('z2neg', 5, 3);
            $table->decimal('z1neg', 5, 3);
            $table->decimal('z0', 5, 3);
            $table->decimal('z1', 5, 3);
            $table->decimal('z2', 5, 3);
            $table->decimal('z3', 5, 3);
            $table->timestamps();

            $table->unique(['jenis_kelamin', 'length'], 'table12_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table12s');
    }
};
