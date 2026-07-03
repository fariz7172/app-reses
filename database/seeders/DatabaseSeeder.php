<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Dummy Kecamatan
        $kecamatans = [
            ['nama_kecamatan' => 'Cilincing'],
            ['nama_kecamatan' => 'Kelapa Gading'],
            ['nama_kecamatan' => 'Koja'],
        ];
        foreach ($kecamatans as $kec) {
            \App\Models\Kecamatan::create($kec);
        }

        // Dummy Kelurahan
        $kelurahans = [
            ['id_kecamatan' => 1, 'nama_kelurahan' => 'Cilincing'],
            ['id_kecamatan' => 1, 'nama_kelurahan' => 'Semper Barat'],
            ['id_kecamatan' => 2, 'nama_kelurahan' => 'Kelapa Gading Barat'],
            ['id_kecamatan' => 2, 'nama_kelurahan' => 'Pegangsaan Dua'],
            ['id_kecamatan' => 3, 'nama_kelurahan' => 'Koja'],
            ['id_kecamatan' => 3, 'nama_kelurahan' => 'Lagoa'],
        ];
        foreach ($kelurahans as $kel) {
            \App\Models\Kelurahan::create($kel);
        }

        // Dummy Dewan
        $dewans = [
            ['nama' => 'H. Ahmad', 'komisi' => 'Komisi A', 'pimpinan_dprd' => 'Tidak'],
            ['nama' => 'Budi Santoso', 'komisi' => 'Komisi B', 'pimpinan_dprd' => 'Ya'],
            ['nama' => 'Siti Aminah', 'komisi' => 'Komisi C', 'pimpinan_dprd' => 'Tidak'],
        ];
        foreach ($dewans as $d) {
            \App\Models\Dewan::create($d);
        }
    }
}
