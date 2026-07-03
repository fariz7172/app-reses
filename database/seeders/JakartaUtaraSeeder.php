<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JakartaUtaraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jakartaUtara = [
            'Cilincing' => ['Kali Baru', 'Cilincing', 'Semper Barat', 'Semper Timur', 'Sukapura', 'Rorotan', 'Marunda'],
            'Kelapa Gading' => ['Kelapa Gading Barat', 'Kelapa Gading Timur', 'Pegangsaan Dua'],
            'Koja' => ['Koja', 'Tugu Utara', 'Tugu Selatan', 'Lagoa', 'Rawa Badak Utara', 'Rawa Badak Selatan'],
            'Pademangan' => ['Pademangan Timur', 'Pademangan Barat', 'Ancol'],
            'Penjaringan' => ['Penjaringan', 'Pluit', 'Pejagalan', 'Kapuk Muara', 'Kamal Muara'],
            'Tanjung Priok' => ['Tanjung Priok', 'Kebon Bawang', 'Sungai Bambu', 'Papanggo', 'Warakas', 'Sunter Agung', 'Sunter Jaya']
        ];

        foreach ($jakartaUtara as $kecamatan => $kelurahans) {
            $kec = \App\Models\Kecamatan::firstOrCreate(['nama_kecamatan' => $kecamatan]);
            foreach ($kelurahans as $kelurahan) {
                \App\Models\Kelurahan::firstOrCreate([
                    'id_kecamatan' => $kec->id,
                    'nama_kelurahan' => $kelurahan
                ]);
            }
        }
    }
}
