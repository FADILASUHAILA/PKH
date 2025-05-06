<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Alternatif>
 */
class AlternatifFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode' => 'A' . $this->faker->unique()->bothify('??##'),
            'nama' => $this->faker->name(),
            'desa_id' => \App\Models\Desa::inRandomOrder()->first()->id ?? 
                         \App\Models\Desa::factory()->create()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}