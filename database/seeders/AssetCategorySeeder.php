<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssetCategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('asset_categories')->insert([
            ['name' => 'Tower', 'slug' => 'tower', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Access Point', 'slug' => 'access-point', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Router', 'slug' => 'router', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Switch', 'slug' => 'switch', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Firewall', 'slug' => 'firewall', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Modem', 'slug' => 'modem', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Server Baremetal', 'slug' => 'server-baremetal', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Server Fisik', 'slug' => 'server-fisik', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
