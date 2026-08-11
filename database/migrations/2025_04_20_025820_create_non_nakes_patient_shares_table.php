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
        Schema::create('non_nakes_patient_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('shared_by')->constrained('users')->cascadeOnDelete(); // User Non Nakes
            $table->foreignId('shared_to')->constrained('users')->cascadeOnDelete(); // Bisa Nakes atau Non Nakes

            $table->enum('status', ['pending', 'accepted', 'rejected', 'revoked'])->default('pending');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('revoked_at')->nullable();

            $table->timestamps();

            $table->unique(['patient_id', 'shared_to']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('non_nakes_patient_shares');
    }
};
