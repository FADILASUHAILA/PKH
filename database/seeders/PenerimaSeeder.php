<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penerima;
use App\Models\Desa;
use App\Models\Kriteria;

class PenerimaSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada data desa dan kriteria terlebih dahulu
        if (Desa::count() === 0) {
            $this->call(DesaTableSeeder::class);
        }

        $penerimaContoh = [
            [
                'nama' => 'Budi Santoso',
                'nik' => '3271234567890123',
                'tmpt_tgl_lahir' => 'Bandung, 15-01-1985',
                'jenis_kelamin' => 'Pria',
                'no_hp' => '081234567890',
                'desa_id' => Desa::inRandomOrder()->first()->id,
            ],
            [
                'nama' => 'Ani Wijaya',
                'nik' => '3272234567890124',
                'tmpt_tgl_lahir' => 'Jakarta, 20-05-1990',
                'jenis_kelamin' => 'Wanita',
                'no_hp' => '082345678901',
                'desa_id' => Desa::inRandomOrder()->first()->id,
            ],
            [
                'nama' => 'Citra Dewi',
                'nik' => '3273234567890125',
                'tmpt_tgl_lahir' => 'Surabaya, 10-11-1978',
                'jenis_kelamin' => 'Wanita',
                'no_hp' => '083456789012',
                'desa_id' => Desa::inRandomOrder()->first()->id,
            ],
            [
                'nama' => 'Doni Pratama',
                'nik' => '3273234567890135',
                'tmpt_tgl_lahir' => 'Surabaya, 10-11-1978',
                'jenis_kelamin' => 'Pria',
                'no_hp' => '083456789012',
                'desa_id' => Desa::inRandomOrder()->first()->id,
            ],
            [
                'nama' => 'Eka Putri',
                'nik' => '3273234567890135',
                'tmpt_tgl_lahir' => 'Surabaya, 10-11-1978',
                'jenis_kelamin' => 'Wanita',
                'no_hp' => '083456789012',
                'desa_id' => Desa::inRandomOrder()->first()->id,
            ],
        ];

        foreach ($penerimaContoh as $penerima) {
            Penerima::create($penerima);
        }

        // Untuk membuat data dummy dalam jumlah banyak
        // Penerima::factory()->count(50)->create();
    }
}