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
        Schema::create('kpis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assessor_id')->constrained('users')->onDelete('cascade');
            $table->date('period_date'); // To track the assessment period (e.g. Month-Year)
            $table->json('behavior_scores'); // Storing individual scores
            $table->string('total_value', 10); // Calculation of sub-scores based on percentage per PDF
            $table->string('index_score', 50); // e.g. "Mencapai Target"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpis');
    }
};
