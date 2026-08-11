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
        Schema::create('table9', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->integer('days');
            $table->integer('week');
            $table->decimal('z3neg', 5, 2);
            $table->decimal('z2neg', 5, 2);
            $table->decimal('z1neg', 5, 2);
            $table->decimal('z0', 5, 2);
            $table->decimal('z1', 5, 2);
            $table->decimal('z2', 5, 2);
            $table->decimal('z3', 5, 2);
            $table->timestamps();

            $table->unique(['jenis_kelamin', 'days', 'week'], 'table9_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table9s');
    }
};
