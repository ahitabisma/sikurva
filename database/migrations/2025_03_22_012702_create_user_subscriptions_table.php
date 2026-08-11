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
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique()->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('instansi_id')->nullable()->constrained('instansis')->cascadeOnDelete();
            $table->foreignId('subscription_id')->constrained('subscriptions')->cascadeOnDelete();
            $table->integer('price');
            $table->integer('point');
            $table->integer('duration'); // months
            $table->enum('duration_type', ['bulan', 'tahun'])->default('bulan');
            $table->enum('status', ['paid', 'pending', 'cancelled', 'expired', 'refunded'])->default('pending');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('expired_at')->nullable();

            // Fields for midtrans integration
            $table->string('payment_type')->nullable();
            $table->string('snap_token')->nullable();
            $table->string('snap_url')->nullable();
            $table->json('payment_details')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_subscriptions');
    }
};
