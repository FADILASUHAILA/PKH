<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alternatif;
use App\Models\Desa;

class AlternatifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada data desa terlebih dahulu
        if (Desa::count() === 0) {
            $this->call(DesaTableSeeder::class);
        }

        // Data contoh alternatif
        $alternatifs = [
            ['kode' => 'A1', 'nama' => 'Budi Santoso', 'desa_id' => Desa::inRandomOrder()->first()->id],
            ['kode' => 'A2', 'nama' => 'Ani Wijaya', 'desa_id' => Desa::inRandomOrder()->first()->id],
            ['kode' => 'A3', 'nama' => 'Citra Dewi', 'desa_id' => Desa::inRandomOrder()->first()->id],
            ['kode' => 'A4', 'nama' => 'Doni Pratama', 'desa_id' => Desa::inRandomOrder()->first()->id],
            ['kode' => 'A5', 'nama' => 'Eka Putri', 'desa_id' => Desa::inRandomOrder()->first()->id],
        ];

        foreach ($alternatifs as $alternatif) {
            Alternatif::create($alternatif);
        }

        // Atau gunakan factory untuk membuat data dummy
        // Alternatif::factory()->count(20)->create();
    }
}