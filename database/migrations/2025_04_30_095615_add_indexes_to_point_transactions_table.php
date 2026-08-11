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
            // Index tambahan
            $table->index('user_id');
            $table->index('instansi_id');
            $table->index('point_batch_id');
            $table->index('type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('point_transactions', function (Blueprint $table) {
            $table->dropIndex('user_id');
            $table->dropIndex('instansi_id');
            $table->dropIndex('point_batch_id');
            $table->dropIndex('type');
            $table->dropIndex('created_at');
        });
    }
};
