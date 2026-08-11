<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('antro_patients', function (Blueprint $table) {
            $table->index(['patient_id', 'tgl_periksa'], 'idx_patient_tgl');
            $table->index('created_by', 'idx_created_by');
        });
    }

    public function down(): void
    {
        Schema::table('antro_patients', function (Blueprint $table) {
            $table->dropIndex('idx_patient_tgl');
            $table->dropIndex('idx_created_by');
        });
    }
};
