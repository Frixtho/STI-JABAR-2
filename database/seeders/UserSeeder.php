<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@pln.co.id',
            'password' => bcrypt('password123'),
            'role' => 'STI', // <-- Ubah jadi 'STI'
            'status' => 'Aktif',
            'nip' => '123456789',
            'department' => 'STI JABAR',
        ]);

        \App\Models\User::create([
            'name' => 'Staf Biasa',
            'email' => 'staff@pln.co.id',
            'password' => bcrypt('password123'),
            'role' => 'IT Support', // <-- Ubah jadi 'IT Support'
            'status' => 'Aktif',
            'nip' => '987654321',
            'department' => 'STI JABAR',
        ]);
    }
}