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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->nullable()->constrained('instansis')->nullOnDelete();
            $table->unsignedBigInteger('referrer_id')->nullable(); // ID referrer (user atau instansi)
            $table->string('referrer_type')->nullable(); // Tipe referrer (App\Models\User atau App\Models\Instansi)
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone');
            $table->text('address');
            $table->boolean('is_nakes');
            $table->boolean('status')->default(true);
            $table->string('referral_code')->unique()->nullable(); // Non Nakes
            $table->integer('points')->nullable(); // Non Nakes
            $table->string('header')->nullable();
            $table->string('kode_lokal', 3)->nullable();
            $table->rememberToken();
            $table->timestamp('last_activity')->nullable();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
