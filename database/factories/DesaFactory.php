<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DesaFactory extends Factory
{
    protected $indonesianVillages = [
        'Ciburial', 'Cikahuripan', 'Cikidang', 'Cileunyi', 'Cimahi',
        'Cipayung', 'Cisitu', 'Ciwidey', 'Dago', 'Gandaria',
        'Garut', 'Jatihandap', 'Kopo', 'Lembang', 'Margahayu',
        'Mekarwangi', 'Padasuka', 'Rancaekek', 'Sarijadi', 'Sukajadi',
        'Sukaraja', 'Tanjungsari', 'Ujungberung', 'Batulayang', 'Cibiru',
        'Cimenyan', 'Ciparay', 'Dayeuhkolot', 'Margaasih', 'Nagreg',
        'Pacet', 'Pangalengan', 'Pasirjambu', 'Rancabali', 'Sindangkerta',
        'Sukasari', 'Cilengkrang', 'Cileunyi Kulon', 'Cimekar', 'Cinunuk',
        'Jatinangor', 'Kutawaringin', 'Margacinta', 'Mekarmanik', 'Neglasari',
        'Pamekaran', 'Rancaekek Kencana', 'Soreang', 'Sukamulya', 'Tanjungwangi'
    ];

    public function definition(): array
    {
        return [
            'nama_desa' => $this->faker->unique()->randomElement($this->indonesianVillages),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}