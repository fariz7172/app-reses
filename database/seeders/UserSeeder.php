<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin Account
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Kasudin Account
        User::updateOrCreate(
            ['email' => 'kasudin@gmail.com'],
            [
                'name' => 'Kepala Suku Dinas',
                'password' => Hash::make('password'),
                'role' => 'kasudin',
            ]
        );
    }
}
