<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Ubah nama tabel
        Schema::rename('non_nakes_patient_shares', 'patient_shares');

        // Tambah index baru (selain unique yang sudah ada)
        Schema::table('patient_shares', function (Blueprint $table) {
            $table->index('shared_by');
            $table->index('shared_to');
            $table->index('status');
        });
    }

    public function down()
    {
        // Kembalikan nama tabel
        Schema::rename('patient_shares', 'non_nakes_patient_shares');

        // Hapus index baru
        Schema::table('non_nakes_patient_shares', function (Blueprint $table) {
            $table->dropIndex(['shared_by']);
            $table->dropIndex(['shared_to']);
            $table->dropIndex(['status']);
        });
    }
};
