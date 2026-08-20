<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AssetCategory;
use App\Models\AssetCategoryField;

class AssetCategoryFieldSeeder extends Seeder
{
    public function run()
    {
        // 1. Definisikan Atribut Umum (Berlaku untuk SEMUA kategori)
        $atributUmum = [
            ['name' => 'ID Aset', 'type' => 'text', 'req' => true],
            ['name' => 'Tanggal Mulai Aktif / Perolehan', 'type' => 'date', 'req' => false],
            ['name' => 'Status Kepemilikan', 'type' => 'select', 'req' => false, 'opt' => ['Milik Sendiri', 'Sewa', 'Pinjam Pakai']],
            ['name' => 'Ket. Status Kepemilikan', 'type' => 'text', 'req' => false],
            ['name' => 'Status Kondisi Aset', 'type' => 'select', 'req' => true, 'opt' => ['Baik', 'Rusak Ringan', 'Rusak Berat']],
            ['name' => 'Status Operasional', 'type' => 'select', 'req' => true, 'opt' => ['Aktif', 'Standby', 'Dalam Perbaikan', 'Afkir']],
            ['name' => 'Tingkat Kritikalitas', 'type' => 'select', 'req' => true, 'opt' => ['Normal', 'Tinggi', 'Sangat Tinggi']],
            ['name' => 'Klasifikasi Keamanan', 'type' => 'select', 'req' => false, 'opt' => ['Internal', 'Rahasia', 'Sangat Rahasia', 'Publik']],
            ['name' => 'Lokasi Aset Saat Ini (Kode)', 'type' => 'text', 'req' => true],
            ['name' => 'Tanggal Pemeriksaan Terakhir', 'type' => 'date', 'req' => false],
            ['name' => 'Deskripsi / Peran Aset', 'type' => 'textarea', 'req' => false],
            ['name' => 'Keterangan Lokasi Aset', 'type' => 'textarea', 'req' => false],
            ['name' => 'PIC Pencatat', 'type' => 'text', 'req' => true],
            ['name' => 'Bidang Pencatat Aset', 'type' => 'text', 'req' => false],
        ];

        // 2. Definisikan Atribut Spesifik (Contoh: Server Baremetal)
        $atributSpesifikServer = [
            ['name' => 'Hostname', 'type' => 'text', 'req' => false],
            ['name' => 'Merk', 'type' => 'text', 'req' => true],
            ['name' => 'Model', 'type' => 'text', 'req' => true],
            ['name' => 'Serial Number', 'type' => 'text', 'req' => false],
            ['name' => 'CPU', 'type' => 'text', 'req' => false],
            ['name' => 'RAM', 'type' => 'text', 'req' => false],
            ['name' => 'Disk Array', 'type' => 'text', 'req' => false],
            ['name' => 'Kapasitas Total Storage', 'type' => 'text', 'req' => false],
        ];

        $categories = AssetCategory::where('slug', '!=', 'tower')->get();

        foreach ($categories as $category) {
            // Hapus yang lama agar tidak dobel jika di-run ulang
            AssetCategoryField::where('asset_category_id', $category->id)->delete();

            $order = 1;
            
            // Masukkan Atribut Umum
            foreach ($atributUmum as $field) {
                AssetCategoryField::create([
                    'asset_category_id' => $category->id,
                    'group_name' => 'ATRIBUT UMUM',
                    'name' => $field['name'],
                    'field_key' => str_replace('-', '_', \Illuminate\Support\Str::slug($field['name'])),
                    'field_type' => $field['type'],
                    'options' => $field['opt'] ?? null,
                    'is_required' => $field['req'],
                    'show_in_table' => in_array($field['name'], ['ID Aset', 'Status Operasional']), // Munculkan bbrp aja di tabel
                    'order_position' => $order++,
                ]);
            }

            // Khusus Server Baremetal, tambahkan atribut spesifiknya
            if ($category->slug === 'server-baremetal') {
                foreach ($atributSpesifikServer as $field) {
                    AssetCategoryField::create([
                        'asset_category_id' => $category->id,
                        'group_name' => 'ATRIBUT SPESIFIK SERVER',
                        'name' => $field['name'],
                        'field_key' => str_replace('-', '_', \Illuminate\Support\Str::slug($field['name'])),
                        'field_type' => $field['type'],
                        'options' => null,
                        'is_required' => $field['req'],
                        'show_in_table' => in_array($field['name'], ['Hostname', 'Merk']),
                        'order_position' => $order++,
                    ]);
                }
            }
        }
    }
}