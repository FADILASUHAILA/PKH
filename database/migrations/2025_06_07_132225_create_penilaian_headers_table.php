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
        Schema::create('penilaian_headers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alternatif_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');  // Tambahkan iniff
            $table->dateTime('tanggal_penilaian');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_headers');
    }
};
