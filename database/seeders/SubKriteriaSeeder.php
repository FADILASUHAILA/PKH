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
        // if (Kriteria::count() === 0) {
        //     $this->call(KriteriaSeeder::class);
        // }

        // Data sub kriteria untuk setiap kriteria
        $subKriteriaData = [
            'K1' => [ // Penghasilan
                ['≤ Rp 600.000/bulan', 100],
                ['Rp 600.000 – Rp 1.000.000/bulan', 75],
                ['> Rp 1.000.000/bulan', 50]
            ],
            'K2' => [ // Pekerjaan
                ['Tidak bekerja', 100],
                ['Pekerja harian lepas', 75],
                ['Pekerja tetap', 50]
            ],
            'K3' => [ // Jumlah Tanggungan
                ['≥5 orang', 100],
                ['3-4 orang', 75],
                ['≤2 orang', 50]
            ],
            'K4' => [ // Jumlah Anak Sekolah
                ['≥3 anak', 100],
                ['2 anak', 75],
                ['1 anak', 50],
                ['Tidak ada anak sekolah', 0]
            ],
            'K5' => [ // Ibu Hamil
                ['Ada', 100],
                ['Tidak ada', 0]
            ],
            'K6' => [ // Balita (0-6 thn)
                ['Ada', 100],
                ['Tidak ada', 0]
            ],
            'K7' => [ // Disabilitas Berat
                ['Ada', 100],
                ['Tidak ada', 0]
            ],
            'K8' => [ // Lansia ≥70 thn
                ['Ada', 100],
                ['Tidak ada', 0]
            ],
            'K9' => [ // Luas Lantai
                ['<8 m² per orang', 100],
                ['8-15 m² per orang', 75],
                ['>15 m² per orang', 50]
            ],
            'K10' => [ // Jenis Lantai
                ['Tanah', 100],
                ['Bambu', 75],
                ['semen', 50],
                ['Keramik', 25]
            ],
            'K11' => [ // Jenis Dinding
                ['Bambu/rumbia/kayu rendah', 100],
                ['Tembok/Semen', 75]
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
    }
}