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
        Schema::create('_hasil__penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alternatif_id')->constrained()->cascadeOnDelete();
            $table->foreignId('kriteria_id')->constrained()->cascadeOnDelete();
            $table->string('nilai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_hasil__penilaian');
    }
};
