<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Desa;

class DesaTableSeeder extends Seeder
{
    protected $realIndonesianVillages = [
        'Adipala', 'Banjarnegara', 'Banyumas', 'Baturraden', 'Brebes',
        'Cilacap', 'Cipari', 'Dayeuhluhur', 'Gandrungmangu', 'Gumelar',
        'Jeruklegi', 'Karangpucung', 'Kawunganten', 'Kebumen', 'Kedungbanteng',
        'Kroya', 'Kutowinangun', 'Majenang', 'Maos', 'Nusawungu',
        'Patimuan', 'Pekalongan', 'Pemalang', 'Purbalingga', 'Purwokerto',
        'Purworejo', 'Randudongkal', 'Sidareja', 'Sumpiuh', 'Tegal',
        'Wangon', 'Watukumpul', 'Wonosobo', 'Ajibarang', 'Bumiayu',
        'Ciamis', 'Cihaurbeuti', 'Cijulang', 'Cimaragas', 'Cipatujah',
        'Cisaga', 'Cisolok', 'Garut', 'Indihiang', 'Jatiwaras',
        'Kawali', 'Lakbok', 'Mangunreja', 'Panjalu', 'Rajadesa'
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