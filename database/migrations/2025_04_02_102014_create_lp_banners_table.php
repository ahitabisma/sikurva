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
        Schema::create('lp_banners', function (Blueprint $table) {
            $table->id();
            $table->string('bg_banner');  // Path gambar latar belakang banner
            $table->string('title');      // Judul utama pada banner
            $table->string('subtitle');   // Subjudul pada banner
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lp_banners');
    }
};
