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
        Schema::create('table4', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->decimal('length', 8, 1);
            $table->decimal('l', 18, 15);
            $table->decimal('m', 18, 15);
            $table->decimal('s', 18, 15);
            $table->decimal('sd4neg', 18, 15);
            $table->decimal('sd3neg', 18, 15);
            $table->decimal('sd2neg', 18, 15);
            $table->decimal('sd1neg', 18, 15);
            $table->decimal('sd0', 18, 15);
            $table->decimal('sd1', 18, 15);
            $table->decimal('sd2', 18, 15);
            $table->decimal('sd3', 18, 15);
            $table->decimal('sd4', 18, 15);
            $table->timestamps();

            $table->unique(['jenis_kelamin', 'length']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table4');
    }
};
