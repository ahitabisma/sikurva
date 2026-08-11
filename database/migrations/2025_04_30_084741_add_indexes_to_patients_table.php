<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->index('kode_lokal');
            $table->index('nama');
            $table->index('created_by');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropIindex('kode_lokal');
            $table->dropIindex('nama');
            $table->dropIindex('created_by');
        });
    }
};
