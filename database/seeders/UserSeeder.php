<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User Admin
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin12345'),
                'role' => 'admin',
            ]
        );

        // User Scan Absen 1
        User::updateOrCreate(
            ['username' => 'scan1'],
            [
                'name' => 'Petugas Scan Absen 1',
                'email' => 'scan1@example.com',
                'password' => Hash::make('scan12345'),
                'role' => 'scanabsen',
            ]
        );

        // User Scan Absen 2
        User::updateOrCreate(
            ['username' => 'scan2'],
            [
                'name' => 'Petugas Scan Absen 2',
                'email' => 'scan2@example.com',
                'password' => Hash::make('scan12345'),
                'role' => 'scanabsen',
            ]
        );

        // User Scan Absen 3
        User::updateOrCreate(
            ['username' => 'scan3'],
            [
                'name' => 'Petugas Scan Absen 3',
                'email' => 'scan3@example.com',
                'password' => Hash::make('scan12345'),
                'role' => 'scanabsen',
            ]
        );
    }
}
