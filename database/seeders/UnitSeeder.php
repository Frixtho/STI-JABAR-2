<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    public function run()
    {
        $unitHierarchy = [
            'PUSHARLIS'       => 1,
            'UIT JBT'         => 1,
            'UID JAWA BARAT'  => 1,
            'UIP JBT'         => 1,
            'UP2B JAWA BARAT' => 1,
            'UP2D JAWA BARAT' => 1,
            
            'UP3 BANDUNG'     => 2,
            'UP3 CIANJUR'     => 2,
            'UP3 CIMAHI'      => 2,
            'UP3 CIREBON'     => 2,
            'UP3 GARUT'       => 2,
            'UP3 INDRAMAYU'   => 2,
            'UP3 KARAWANG'    => 2,
            'UP3 MAJALAYA'    => 2,
            'UP3 PURWAKARTA'  => 2,
            'UP3 SUKABUMI'    => 2,
            'UP3 SUMEDANG'    => 2,
            'UP3 TASIKMALAYA' => 2,
            'UPT CIREBON'     => 2,
            'UPT KARAWANG'    => 2,
            'USAT CIRATA'     => 2,
            'STI JABAR'       => 2,
        ];

        foreach ($unitHierarchy as $name => $level) {
            Unit::updateOrCreate(
                ['name' => $name],
                [
                    'name'  => $name,
                    'level' => $level,
                    'type'  => 'Unit' // Menambahkan nilai default untuk kolom type agar lolos validasi NOT NULL
                ]
            );
        }
    }
}