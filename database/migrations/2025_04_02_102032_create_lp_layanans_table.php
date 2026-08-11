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
        Schema::create('lp_layanans', function (Blueprint $table) {
            $table->id();
            $table->string('image');       // Path icon layanan
            $table->string('title');      // Judul layanan
            $table->text('description');  // Deskripsi layanan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lp_layanans');
    }
};
