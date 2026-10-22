<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@kementerian.go.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Akun Pegawai
        User::create([
            'name' => 'Pegawai Kementerian',
            'email' => 'pegawai@kementerian.go.id',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);
    }
}