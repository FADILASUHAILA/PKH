<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubKriteria;
use App\Models\Kriteria;

class SubKriteriaSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada data kriteria terlebih dahulu
        if (Kriteria::count() === 0) {
            $this->call(KriteriaSeeder::class);
        }

        // Data sub kriteria untuk setiap kriteria
        $subKriteriaData = [
            'K1' => [
                ['Kurang dari Rp.500.000', 1],
                ['Rp.500.000 - Rp.1.000.000', 2],
                ['Rp.1.000.000 - Rp.2.000.000', 3],
                ['Lebih dari Rp.2.000.000', 4]
            ],
            'K2' => [
                ['Tidak Bekerja', 1],
                ['Serabutan', 2],
                ['Buruh/Wiraswasta', 3],
                ['PNS/Karyawan Swasta', 4]
            ],
            'K3' => [
                ['1-2 Orang', 1],
                ['3-4 Orang', 2],
                ['5-6 Orang', 3],
                ['Lebih dari 6 Orang', 4]
            ],
            'K4' => [
                ['Tidak Ada', 1],
                ['1 Orang', 2],
                ['2 Orang', 3],
                ['3 Orang atau lebih', 4]
            ],
            'K5' => [
                ['Menumpang', 1],
                ['Kontrak/Sewa', 2],
                ['Milik Sendiri (Non SHM)', 3],
                ['Milik Sendiri (SHM)', 4]
            ],
            'K6' => [
                ['Tidak Layak Huni', 1],
                ['Sederhana', 2],
                ['Menengah', 3],
                ['Mewah', 4]
            ],
            'K7' => [
                ['Tidak Ada', 1],
                ['1 Orang', 3],
                ['2 Orang atau lebih', 5]
            ]
        ];

        foreach ($subKriteriaData as $kodeKriteria => $subKriterias) {
            $kriteria = Kriteria::where('kode', $kodeKriteria)->first();
            
            if ($kriteria) {
                foreach ($subKriterias as $sub) {
                    SubKriteria::create([
                        'nama_sub_kriteria' => $sub[0],
                        'nilai' => $sub[1],
                        'kriteria_id' => $kriteria->id
                    ]);
                }
            }
        }

        // Untuk membuat data dummy tambahan
        // SubKriteria::factory()->count(10)->create();
    }
}