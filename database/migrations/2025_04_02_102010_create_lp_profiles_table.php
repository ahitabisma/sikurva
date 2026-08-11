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
        Schema::create('lp_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');       // Nama pengguna
            $table->string('subtitle');   // Subjudul profil
            $table->text('description');  // Deskripsi singkat
            $table->json('skills');       // List keahlian (disimpan dalam format JSON)
            $table->string('photo');      // Path foto profil
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lp_profiles');
    }
};
