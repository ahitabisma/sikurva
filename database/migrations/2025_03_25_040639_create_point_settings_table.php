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
        Schema::create('point_settings', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['bonus', 'usage']);
            $table->enum('user_type', ['non-nakes', 'nakes'])->nullable();
            $table->string('name');
            $table->integer('points');
            $table->integer('duration')->nullable(); // months
            $table->enum('duration_type', ['bulan', 'tahun'])->nullable();
            $table->timestamps();

            $table->unique(['user_type', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_settings');
    }
};
