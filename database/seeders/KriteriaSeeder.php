<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kriteria;
use App\Models\Alternatif;
use App\Models\Penerima;

class KriteriaSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada data alternatif terlebih dahulu
        // if (Alternatif::count() === 0) {
        //     $this->call(AlternatifSeeder::class);
        // }


        $kriteriaContoh = [
            [
                'kode' => 'K1',
                'nama_kriteria' => 'Penghasilan',
                'bobot' => 25,
            ],
            [
                'kode' => 'K2',
                'nama_kriteria' => 'Pekerjaan',
                'bobot' => 10,
            ],
            [
                'kode' => 'K3',
                'nama_kriteria' => 'Jumlah Tanggungan',
                'bobot' => 15,
            ],
            [
                'kode' => 'K4',
                'nama_kriteria' => 'Jumlah Anak Sekolah',
                'bobot' => 10,
            ],
            [
                'kode' => 'K5',
                'nama_kriteria' => 'Ibu Hamil',
                'bobot' => 10,
            ],
            [
                'kode' => 'K6',
                'nama_kriteria' => 'Balita (0-6 tahun)',
                'bobot' => 5,
            ],
            [
                'kode' => 'K7',
                'nama_kriteria' => 'Anggota Disabilitas',
                'bobot' => 10,
            ],

            [
                'kode' => 'K8',
                'nama_kriteria' => 'Lansia (> 60 tahun)',
                'bobot' => 5,
            ],
            [
                'kode' => 'K9',
                'nama_kriteria' => 'Luas Lantai',
                'bobot' => 3,
            ],
            [
                'kode' => 'K10',
                'nama_kriteria' => 'Jenis Lantai',
                'bobot' => 3,
            ],
            [
                'kode' => 'K11',
                'nama_kriteria' => 'Jenis Dinding',
                'bobot' => 4,
            ]

        ];

        foreach ($kriteriaContoh as $kriteria) {
            Kriteria::create($kriteria);
        }

        // Untuk membuat data dummy dalam jumlah banyak
        // Kriteria::factory()->count(10)->create();
    }
}
