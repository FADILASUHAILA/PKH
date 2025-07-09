<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\SubKriteria;
use App\Models\Penilaian;
use App\Models\PenilaianHeader;

class PenilaianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data penilaian yang sudah ada
        Penilaian::truncate();
        
        // Ambil semua alternatif, kriteria, dan subkriteria
        $alternatifs = Alternatif::all();
        $kriterias = Kriteria::with('subkriterias')->get();
        
        // // Buat header penilaian
        // $header = PenilaianHeader::create([
        //     'tanggal_penilaian' => now(),
        //     'catatan' => 'Data penilaian awal'
        // ]);
        
        foreach ($alternatifs as $alternatif) {
            foreach ($kriterias as $kriteria) {
                // Ambil subkriteria secara acak untuk kriteria ini
                $subkriteria = $kriteria->subkriterias->random();
                
                // Buat penilaian
                Penilaian::create([
                    'alternatif_id' => $alternatif->id,
                    'kriteria_id' => $kriteria->id,
                    'subkriteria_id' => $subkriteria->id,
                    'nilai' => $subkriteria->nilai,
                    // 'header_id' => $header->id
                ]);
            }
        }
    }
}