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
        Schema::create('hasil_penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penilaian_id')->constrained('penilaians')->onDelete('cascade');
            $table->foreignId('alternatif_id')->constrained('alternatifs')->onDelete('cascade');
            $table->json('decision_matrix')->comment('Matriks keputusan');
            $table->json('preference_matrix')->comment('Matriks preferensi');
            $table->decimal('leaving_flow', 10, 6)->comment('Phi+ (Leaving Flow)');
            $table->decimal('entering_flow', 10, 6)->comment('Phi- (Entering Flow)');
            $table->decimal('net_flow', 10, 6)->comment('Phi (Net Flow)');
            $table->unsignedInteger('ranking');
            $table->timestamps();
            
            // Composite index for better query performance
            $table->index(['penilaian_id', 'alternatif_id']);
            $table->index(['penilaian_id', 'ranking']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_penilaian');
    }
};