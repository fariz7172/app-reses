<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RoleUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'role' => 'Super Admin',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Sudin',
                'email' => 'sudin@gmail.com',
                'role' => 'Sudin',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Kasubag',
                'email' => 'kasubag@gmail.com',
                'role' => 'Kasubag',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Kecamatan Cilincing',
                'email' => 'cilincing@gmail.com',
                'role' => 'Kecamatan',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Kecamatan Kelapa Gading',
                'email' => 'kelapagading@gmail.com',
                'role' => 'Kecamatan',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Kecamatan Tanjung Priok',
                'email' => 'priok@gmail.com',
                'role' => 'Kecamatan',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Kecamatan Pademangan',
                'email' => 'pademangan@gmail.com',
                'role' => 'Kecamatan',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Kecamatan Penjaringan',
                'email' => 'penjaringan@gmail.com',
                'role' => 'Kecamatan',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Kecamatan Koja',
                'email' => 'koja@gmail.com',
                'role' => 'Kecamatan',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
