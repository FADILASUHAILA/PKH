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
        if (Alternatif::count() === 0) {
            $this->call(AlternatifSeeder::class);
        }
        

        $kriteriaContoh = [
            [
                'kode' => 'K1',
                'nama_kriteria' => 'Penghasilan',
                'bobot' => 5,
                'alternatif_id' => Alternatif::inRandomOrder()->first()->id,
            ],
            [
                'kode' => 'K2',
                'nama_kriteria' => 'Status Pekerjaan',
                'bobot' => 4,
                'alternatif_id' => Alternatif::inRandomOrder()->first()->id,
            ],
            [
                'kode' => 'K3',
                'nama_kriteria' => 'Jumlah Tanggungan',
                'bobot' => 3,
                'alternatif_id' => Alternatif::inRandomOrder()->first()->id,
            ],
            [
                'kode' => 'K4',
                'nama_kriteria' => 'Jumlah Anak Sekolah',
                'bobot' => 4,
                'alternatif_id' => Alternatif::inRandomOrder()->first()->id,
            ],
            [
                'kode' => 'K5',
                'nama_kriteria' => 'Status Kepemilikian Rumah',
                'bobot' => 3,
                'alternatif_id' => Alternatif::inRandomOrder()->first()->id,
            ],
            [
                'kode' => 'K6',
                'nama_kriteria' => 'Kualitas Bangunan',
                'bobot' => 6,
                'alternatif_id' => Alternatif::inRandomOrder()->first()->id,
            ],
            [
                'kode' => 'K7',
                'nama_kriteria' => 'Anggota Disabilitas',
                'bobot' => 8,
                'alternatif_id' => Alternatif::inRandomOrder()->first()->id,
            ],
        ];

        foreach ($kriteriaContoh as $kriteria) {
            Kriteria::create($kriteria);
        }

        // Untuk membuat data dummy dalam jumlah banyak
        // Kriteria::factory()->count(10)->create();
    }
}