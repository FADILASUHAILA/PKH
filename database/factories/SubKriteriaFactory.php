<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SubKriteriaFactory extends Factory
{
    public function definition(): array
    {
        // Data sub kriteria untuk setiap kriteria yang ada di seeder
        $subKriteriaMap = [
            'Penghasilan' => [
                ['Kurang dari Rp.500.000', 1],
                ['Rp.500.000 - Rp.1.000.000', 2],
                ['Rp.1.000.000 - Rp.2.000.000', 3],
                ['Lebih dari Rp.2.000.000', 4]
            ],
            'Status Pekerjaan' => [
                ['Tidak Bekerja', 1],
                ['Serabutan', 2],
                ['Buruh/Wiraswasta', 3],
                ['PNS/Karyawan Swasta', 4]
            ],
            'Jumlah Tanggungan' => [
                ['1-2 Orang', 1],
                ['3-4 Orang', 2],
                ['5-6 Orang', 3],
                ['Lebih dari 6 Orang', 4]
            ],
            'Jumlah Anak Sekolah' => [
                ['Tidak Ada', 1],
                ['1 Orang', 2],
                ['2 Orang', 3],
                ['3 Orang atau lebih', 4]
            ],
            'Status Kepemilikan Rumah' => [
                ['Menumpang', 1],
                ['Kontrak/Sewa', 2],
                ['Milik Sendiri (Non SHM)', 3],
                ['Milik Sendiri (SHM)', 4]
            ],
            'Kualitas Bangunan' => [
                ['Tidak Layak Huni', 1],
                ['Sederhana', 2],
                ['Menengah', 3],
                ['Mewah', 4]
            ],
            'Anggota Disabilitas' => [
                ['Tidak Ada', 1],
                ['1 Orang', 3],
                ['2 Orang atau lebih', 5]
            ]
        ];

        // Ambil kriteria acak
        $kriteria = \App\Models\Kriteria::inRandomOrder()->first() ?? 
                   \App\Models\Kriteria::factory()->create();

        // Ambil sub kriteria berdasarkan nama kriteria
        $subKriteriaOptions = $subKriteriaMap[$kriteria->nama_kriteria] ?? [
            ['Default Sub Kriteria', 1]
        ];

        $selectedSub = $this->faker->randomElement($subKriteriaOptions);

        return [
            'nama_sub_kriteria' => $selectedSub[0],
            'nilai' => $selectedSub[1],
            'kriteria_id' => $kriteria->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}