<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin PLN',
            'email' => 'admin@pln.co.id',
            'phone' => '081234567890',
            'password' => Hash::make('password123'),
        ]);
    }
}