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
        Schema::create('indikasis', function (Blueprint $table) {
            $table->id();
            $table->integer('penghasilan');
            $table->enum('pekerjaan', ['Tidak bekerja', 'Pekerja harian lepas', 'Pekerja tetap']);
            $table->integer('jumlah_tanggungan');
            $table->integer('jumlah_anak_sekolah');
            $table->enum('ibu_hamil', ['Ada', 'Tidak Ada']);
            $table->enum('balita', ['Ada', 'Tidak Ada']);
            $table->enum('anggota_disabilitas', ['Ada', 'Tidak Ada']);
            $table->enum('lansia', ['Ada', 'Tidak Ada']);
            $table->enum('luas_lantai', ['<8 m² per orang', '8-15 m² per orang', '>15 m² per orang']);
            $table->enum('jenis_lantai', ['Tanah', 'Bambu', 'semen', 'Keramik']);
            $table->enum('jenis_dinding', ['Bambu/rumbia/kayu rendah', 'Tembok/Semen']);
            $table->unsignedBigInteger('alternatif_id');
            $table->softDeletes();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('alternatif_id')->references('id')->on('alternatifs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indikasis');
    }
};