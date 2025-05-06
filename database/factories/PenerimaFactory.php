<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PenerimaFactory extends Factory
{
    public function definition(): array
    {
        $jenisKelamin = $this->faker->randomElement(['Pria', 'Wanita']);
        $firstName = $jenisKelamin === 'Pria' ? $this->faker->firstNameMale() : $this->faker->firstNameFemale();
        
        return [
            'nama' => $firstName . ' ' . $this->faker->lastName(),
            'nik' => $this->faker->unique()->numerify('################'),
            'tmpt_tgl_lahir' => $this->faker->city() . ', ' . $this->faker->date('d-m-Y', '2000-01-01'),
            'jenis_kelamin' => $jenisKelamin,
            'no_hp' => $this->faker->numerify('08##########'),
            'desa_id' => \App\Models\Desa::inRandomOrder()->first()->id ?? 
                         \App\Models\Desa::factory()->create()->id,
            'kriteria_id' => \App\Models\Kriteria::inRandomOrder()->first()->id ?? 
                           \App\Models\Kriteria::factory()->create()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}