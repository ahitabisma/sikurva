<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom points
            $table->dropColumn('points');

            // Tambahkan kolom is_support_header
            $table->boolean('is_support_header')->default(false)->after('referral_code'); // Letakkan setelah referral_code
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Rollback: hapus kolom is_support_header
            $table->dropColumn('is_support_header');

            // Kembalikan kolom points
            $table->integer('points')->nullable()->after('referral_code');
        });
    }
};
