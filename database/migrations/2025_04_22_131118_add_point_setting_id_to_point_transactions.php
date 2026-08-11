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
        Schema::table('point_transactions', function (Blueprint $table) {
            $table->foreignId('point_setting_id')
                ->nullable()
                ->constrained('point_settings')
                ->onDelete('set null');
            $table->foreignId('patient_id')
                ->nullable()
                ->constrained('patients')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('point_transactions', function (Blueprint $table) {
            //
        });
    }
};
