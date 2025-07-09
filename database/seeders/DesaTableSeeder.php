<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Desa;

class DesaTableSeeder extends Seeder
{
    protected $realIndonesianVillages = [
        'Alue Gunto', 'Ampeh', 'Ara', 'Awe', 'Blang', 'Calong', 'Cibrek Baroh', 'Cibrek Tunong'
    ];

    public function run(): void
    {
        // Seed dengan data real
        foreach ($this->realIndonesianVillages as $desa) {
            Desa::create([
                'nama_desa' => $desa,
            ]);
        }

        // Atau bisa juga menggunakan factory untuk tambahan data
        // \App\Models\Desa::factory()->count(20)->create();
    }
}