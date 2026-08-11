<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('instansis', function (Blueprint $table) {
            // Rename kolom dulu
            $table->renameColumn('kode_lokal', 'sender_name');
        });

        Schema::table('instansis', function (Blueprint $table) {
            // Ubah panjang kolom jadi 255 karakter
            $table->string('sender_name', 255)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('instansis', function (Blueprint $table) {
            // Kembalikan panjang ke default (misal 100, tergantung sebelumnya)
            $table->string('sender_name', 3)->nullable()->change();
        });

        Schema::table('instansis', function (Blueprint $table) {
            // Rename kembali
            $table->renameColumn('sender_name', 'kode_lokal');
        });
    }
};
