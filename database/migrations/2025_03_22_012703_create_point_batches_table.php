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
        Schema::create('point_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('instansi_id')->nullable()->constrained('instansis')->cascadeOnDelete();
            $table->foreignId('user_subscription_id')->nullable()->constrained('user_subscriptions')->cascadeOnDelete();
            $table->integer('points');
            $table->integer('remaining_points');
            $table->enum('type', ['purchase', 'referral', 'bonus', 'referrer']);
            $table->timestamp('expired_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poin_batches');
    }
};
