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
        Schema::table('instansis', function (Blueprint $table) {
            // Hapus kolom points
            $table->dropColumn('points');

            // Tambahkan kolom is_support_header
            $table->boolean('is_support_header')->default(false)->after('is_verified'); // Letakkan setelah is_verified
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('instansis', function (Blueprint $table) {
            // Rollback: hapus kolom is_support_header
            $table->dropColumn('is_support_header');

            // Kembalikan kolom points
            $table->integer('points')->nullable()->after('is_verified');
        });
    }
};
