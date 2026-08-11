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
        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('instansi_id')->nullable()->constrained('instansis')->cascadeOnDelete();
            $table->foreignId('point_batch_id')->nullable()->constrained('point_batches')->cascadeOnDelete();
            $table->integer('points');
            $table->enum('type', ['purchase', 'referral', 'bonus', 'usage', 'referrer']);
            $table->string('referral_code')->nullable();
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poin_transactions');
    }
};
