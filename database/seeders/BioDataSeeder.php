<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BioData;
use App\Models\Alternatif;
use Faker\Factory as Faker;

class BioDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada data alternatif terlebih dahulu
        if (Alternatif::count() === 0) {
            $this->call(AlternatifSeeder::class);
        }

        $faker = Faker::create('id_ID');

        // Ambil semua alternatif yang ada
        $alternatifs = Alternatif::all();

        foreach ($alternatifs as $alternatif) {
            // Generate NIK (16 digit Indonesian ID number)
            $nik = $this->generateNIK($faker);
            
            // Generate alamat yang realistis
            $alamat = $this->generateAlamat($faker);
            
            // Generate nomor HP Indonesia
            $noHp = $this->generateNoHP($faker);

            BioData::create([
                'nik' => $nik,
                'alamat' => $alamat,
                'no_hp' => $noHp,
                'alternatif_id' => $alternatif->id
            ]);
        }

        $this->command->info('BioData seeder completed. Generated ' . $alternatifs->count() . ' bio data records.');
    }

    /**
     * Generate realistic Indonesian NIK for Aceh Utara region (16 digits)
     * Format: DDMMYY + Regional Code (6 digits) + Sequential Number (4 digits)
     */
    private function generateNIK($faker): string
    {
        // Generate birth date
        $birthDate = $faker->dateTimeBetween('-60 years', '-17 years'); // Age between 17-60 years
        $day = $birthDate->format('d');
        $month = $birthDate->format('m');
        $year = $birthDate->format('y');
        
        // For female, add 40 to the day (Indonesian NIK standard)
        $gender = $faker->randomElement(['male', 'female']);
        if ($gender === 'female') {
            $day = str_pad((int)$day + 40, 2, '0', STR_PAD_LEFT);
        }
        
        // Aceh Utara regional codes (1107xx format)
        // 1107 = Aceh Utara regency code
        // Last 2 digits represent sub-districts in Aceh Utara
        $acehUtaraSubDistricts = [
            '110701', // Dewantara
            '110702', // Kuta Makmur
            '110703', // Seunuddon
            '110704', // Lhoksukon
            '110705', // Tanah Jambo Aye
            '110706', // Langkahan
            '110707', // Samudera
            '110708', // Syamtalira Bayu
            '110709', // Syamtalira Aron
            '110710', // Lapang
            '110711', // Matangkuli
            '110712', // Cot Girek
            '110713', // Geuredong Pase
            '110714', // Lhok Sukon
            '110715', // Baktiya
            '110716', // Baktiya Barat
            '110717', // Pirak Timu
            '110718', // Nibong
            '110719', // Paya Bakong
            '110720', // Meurah Mulia
            '110721', // Banda Baro
            '110722', // Sawang
            '110723', // Nisam
            '110724', // Nisam Antara
            '110725', // Krueng Simpo
            '110726', // Tanah Luas
            '110727'  // Muara Batu
        ];
        
        $regionalCode = $faker->randomElement($acehUtaraSubDistricts);
        
        // Sequential number (4 digits)
        $sequential = str_pad($faker->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT);
        
        return  $regionalCode . $day . $month . $year . $sequential;
    }

    /**
     * Generate realistic Indonesian address for Aceh Utara region
     */
    private function generateAlamat($faker): string
    {
        // Common street names in Aceh
        $jalanNames = [
            'Medan-Banda Aceh', 'Cut Nyak Dien', 'Teuku Umar', 'Sultan Iskandar Muda',
            'Panglima Polim', 'T. Chik Ditiro', 'Prof. A. Majid Ibrahim', 'Syiah Kuala',
            'Jenderal Sudirman', 'Diponegoro', 'Gajah Mada', 'Veteran'
        ];
        
        // Sub-districts in Aceh Utara
        $kecamatanNames = [
            'Dewantara', 'Kuta Makmur', 'Seunuddon', 'Lhoksukon', 'Tanah Jambo Aye',
            'Langkahan', 'Samudera', 'Syamtalira Bayu', 'Syamtalira Aron', 'Lapang',
            'Matangkuli', 'Cot Girek', 'Geuredong Pase', 'Baktiya', 'Pirak Timu',
            'Nibong', 'Paya Bakong', 'Meurah Mulia', 'Banda Baro', 'Sawang',
            'Nisam', 'Nisam Antara', 'Krueng Simpo', 'Tanah Luas', 'Muara Batu'
        ];
        
        $jalan = $faker->randomElement($jalanNames);
        $nomor = $faker->numberBetween(1, 999);
        $rt = str_pad($faker->numberBetween(1, 20), 3, '0', STR_PAD_LEFT);
        $rw = str_pad($faker->numberBetween(1, 15), 3, '0', STR_PAD_LEFT);
        $gampong = 'Gampong ' . $faker->city; // Gampong is village in Acehnese
        $kecamatan = 'Kec. ' . $faker->randomElement($kecamatanNames);
        
        return "Jl. {$jalan} No. {$nomor}, RT {$rt}/RW {$rw}, {$gampong}, {$kecamatan}, Aceh Utara";
    }

    /**
     * Generate realistic Indonesian phone number
     */
    private function generateNoHP($faker): string
    {
        // Indonesian mobile phone providers
        $providers = [
            '0812', '0813', '0821', '0822', '0823', // Telkomsel
            '0851', '0852', '0853', '0855', '0856', '0857', '0858', // Indosat
            '0817', '0818', '0819', '0859', '0877', '0878', // XL Axiata
            '0895', '0896', '0897', '0898', '0899', // Tri (3)
            '0881', '0882', '0883', '0884', '0885', '0886', '0887', '0888' // Smartfren
        ];
        
        $prefix = $faker->randomElement($providers);
        $suffix = $faker->numerify('########'); // 8 additional digits
        
        return $prefix . $suffix;
    }
}