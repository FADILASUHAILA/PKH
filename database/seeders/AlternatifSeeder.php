<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alternatif;
use App\Models\Desa;
use Faker\Factory as Faker;

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

        $faker = Faker::create('id_ID');

        // Generate 200 data alternatif
        for ($i = 1; $i <= 200; $i++) {
            $gender = $faker->randomElement(['male', 'female']);
            
            $alternatif = [
                'kode' => 'A' . $i,
                'nama' => $gender === 'male' 
                    ? $faker->firstNameMale . ' ' . $faker->lastName
                    : $faker->firstNameFemale . ' ' . $faker->lastName,
                'desa_id' => Desa::inRandomOrder()->first()->id
            ];

            Alternatif::create($alternatif);
        }

        // Atau bisa juga menggunakan factory jika sudah dibuat:
        // Alternatif::factory()->count(200)->create();
    }
}