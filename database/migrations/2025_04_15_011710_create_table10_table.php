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
        Schema::create('table10', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->integer('days');
            $table->integer('week');
            $table->decimal('z3neg', 4, 1);
            $table->decimal('z2neg', 4, 1);
            $table->decimal('z1neg', 4, 1);
            $table->decimal('z0', 4, 1);
            $table->decimal('z1', 4, 1);
            $table->decimal('z2', 4, 1);
            $table->decimal('z3', 4, 1);
            $table->timestamps();

            $table->unique(['jenis_kelamin', 'days', 'week'], 'table10_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table10s');
    }
};
