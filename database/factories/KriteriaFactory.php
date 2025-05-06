<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class KriteriaFactory extends Factory
{
    public function definition(): array
    {
        $jenisKriteria = [
            'Harga', 'Kualitas', 'Pelayanan', 'Lokasi', 'Fasilitas',
            'Kebersihan', 'Keamanan', 'Kenyamanan', 'Reputasi', 'Pengalaman'
        ];

        return [
            'kode' => 'KR-' . $this->faker->unique()->numberBetween(1, 100),
            'nama_kriteria' => $this->faker->randomElement($jenisKriteria),
            'bobot' => $this->faker->numberBetween(1, 5),
            'alternatif_id' => \App\Models\Alternatif::inRandomOrder()->first()->id ??
                              \App\Models\Alternatif::factory()->create()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}