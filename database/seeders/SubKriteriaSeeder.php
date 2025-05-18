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
            'K1' => [ // Penghasilan
                ['≤ Rp 600.000/bulan', 100],
                ['Rp 600.000 – Rp 1.000.000/bulan', 75],
                ['> Rp 1.000.000/bulan', 10]
            ],
            'K2' => [ // Pekerjaan
                ['Tidak bekerja', 100],
                ['Pekerja harian lepas', 75],
                ['Pekerja tetap', 10]
            ],
            'K3' => [ // Jumlah Tanggungan
                ['≥5 orang', 100],
                ['3-4 orang', 75],
                ['≤2 orang', 10]
            ],
            'K4' => [ // Jumlah Anak Sekolah
                ['≥3 anak', 100],
                ['2 anak', 75],
                ['1 anak', 10],
                ['Tidak ada anak sekolah', 5]
            ],
            'K5' => [ // Ibu Hamil
                ['Ada', 30],
                ['Tidak ada', 5]
            ],
            'K6' => [ // Balita (0-6 thn)
                ['Ada', 30],
                ['Tidak ada', 5]
            ],
            'K7' => [ // Disabilitas Berat
                ['Ada', 40],
                ['Tidak ada', 5]
            ],
            'K8' => [ // Lansia ≥70 thn
                ['Ada', 20],
                ['Tidak ada', 5]
            ],
            'K9' => [ // Luas Lantai
                ['<8 m² per orang', 50],
                ['8-15 m² per orang', 25],
                ['>15 m² per orang', 5]
            ],
            'K10' => [ // Jenis Lantai
                ['Tanah/bambu', 50],
                ['Keramik/ubin/semen', 5]
            ],
            'K11' => [ // Jenis Dinding
                ['Bambu/rumbia/kayu rendah', 50],
                ['Tembok/Semen', 5]
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