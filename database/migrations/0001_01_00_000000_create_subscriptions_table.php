<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('point');
            $table->integer('duration'); // months
            $table->enum('duration_type', ['bulan', 'tahun'])->default('bulan');
            $table->decimal('price', 15, 2);
            $table->json('description');
            $table->boolean('status')->default(true)->comment('true = aktif, false = tidak aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
