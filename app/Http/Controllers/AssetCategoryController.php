<?php

namespace App\Http\Controllers;

use App\Models\AssetCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AssetCategoryController extends Controller
{
    public function index()
    {
        $categories = AssetCategory::orderBy('name')->paginate(10);
        return view('manageCategory', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:asset_categories,name']);

        AssetCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return back()->with('success', 'Kategori aset berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $category = AssetCategory::findOrFail($id);
        $request->validate(['name' => 'required|string|unique:asset_categories,name,'.$id]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return back()->with('success', 'Kategori aset berhasil diubah!');
    }

    public function destroy($id)
    {
        AssetCategory::findOrFail($id)->delete();
        return back()->with('success', 'Kategori aset berhasil dihapus!');
    }

    public function toggleStatus($id)
    {
        $category = \App\Models\AssetCategory::findOrFail($id);
        
        // Membalikkan status (Jika true jadi false, jika false jadi true)
        $category->update(['is_active' => !$category->is_active]);
        
        $statusMessage = $category->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return back()->with('success', "Submenu {$category->name} berhasil {$statusMessage}!");
    }

    public function fieldsIndex($id)
    {
        $category = \App\Models\AssetCategory::findOrFail($id);

        // 1. Inisialisasi Atribut Umum secara otomatis ke dalam database
        $defaultFields = [
            ['name' => 'ID Aset', 'field_key' => 'asset_id', 'type' => 'text', 'required' => true],
            ['name' => 'Tanggal Perolehan', 'field_key' => 'acquisition_date', 'type' => 'date', 'required' => false],
            ['name' => 'Status Kepemilikan', 'field_key' => 'ownership_status', 'type' => 'select', 'required' => false],
            ['name' => 'Ket. Status Kepemilikan', 'field_key' => 'ownership_desc', 'type' => 'text', 'required' => false],
            ['name' => 'Status Kondisi', 'field_key' => 'condition_status', 'type' => 'select', 'required' => true],
            ['name' => 'Status Operasional', 'field_key' => 'operational_status', 'type' => 'select', 'required' => true],
            ['name' => 'Tingkat Kritikalitas', 'field_key' => 'criticality_level', 'type' => 'select', 'required' => true],
            ['name' => 'Klasifikasi Keamanan', 'field_key' => 'security_classification', 'type' => 'select', 'required' => false],
            ['name' => 'Lokasi Aset Saat Ini', 'field_key' => 'unit_name', 'type' => 'select', 'required' => true],
            ['name' => 'Keterangan Lokasi', 'field_key' => 'location_desc', 'type' => 'text', 'required' => false],
            ['name' => 'Tanggal Pemeriksaan', 'field_key' => 'last_maintenance_date', 'type' => 'date', 'required' => false],
            ['name' => 'Deskripsi Aset', 'field_key' => 'description', 'type' => 'text', 'required' => false],
            ['name' => 'PIC Pencatat', 'field_key' => 'pic', 'type' => 'text', 'required' => true],
            ['name' => 'Bidang Pencatat', 'field_key' => 'pic_department', 'type' => 'text', 'required' => false],
        ];

        foreach ($defaultFields as $df) {
            \App\Models\AssetCategoryField::firstOrCreate(
                [
                    'asset_category_id' => $category->id,
                    'field_key' => $df['field_key'],
                ],
                [
                    'name' => $df['name'],
                    'field_type' => $df['type'],
                    'is_required' => $df['required'],
                    'show_in_table' => false, // Default mati agar tabel tidak kepenuhan
                    'group_name' => 'ATRIBUT UMUM'
                ]
            );
        }

        return view('manageCategoryFields', compact('category'));
    }

    public function fieldsStore(Request $request, $id)
    {
        $category = AssetCategory::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'field_type' => 'required|in:text,number,date,select',
        ]);

        // Mengubah label "IP Address" menjadi field_key "ip_address"
        $fieldKey = str_replace('-', '_', Str::slug($request->name));

        // Cegah nama kolom duplikat di kategori yang sama
        if ($category->fields()->where('field_key', $fieldKey)->exists()) {
            return back()->with('error', 'Nama kolom ini sudah digunakan di kategori ini.');
        }

        $options = null;
        if ($request->field_type === 'select' && $request->options) {
            // Ubah "Aktif, Rusak, Hilang" menjadi array
            $options = array_map('trim', explode(',', $request->options));
        }

        $category->fields()->create([
            'name' => $request->name,
            'field_key' => $fieldKey,
            'field_type' => $request->field_type,
            'options' => $options,
            'is_required' => $request->has('is_required'),
            'show_in_table' => $request->has('show_in_table'),
            'order_position' => $category->fields()->count() + 1,
        ]);

        return back()->with('success', 'Kolom form berhasil ditambahkan!');
    }

    public function fieldsDestroy($fieldId)
    {
        $field = \App\Models\AssetCategoryField::findOrFail($fieldId);
        $field->delete();
        
        return back()->with('success', 'Kolom form berhasil dihapus!');
    }

    public function unitSettings($id)
    {
        $category = AssetCategory::findOrFail($id);
        
        // Mengambil seluruh nama unit dari database secara dinamis dan diurutkan abjad
        $units = \App\Models\Unit::orderBy('name')->pluck('name')->toArray();
        
        return view('manageCategoryUnit', compact('category', 'units'));
    }

    public function saveUnitSettings(Request $request, $id)
    {
        // Hapus setting lama untuk kategori ini
        \DB::table('category_unit_settings')->where('asset_category_id', $id)->delete();

        if ($request->has('settings')) {
            foreach ($request->settings as $unitName => $status) {
                // 1. KEMBALIKAN SPASI: PHP mengubah spasi menjadi underscore, kita kembalikan lagi.
                $realUnitName = str_replace('_', ' ', $unitName);

                // 2. BOOLEAN AMAN: Pastikan input '1' benar-benar menjadi true
                $isActive = in_array($status, [1, '1', true, 'true', 'on']);

                \DB::table('category_unit_settings')->insert([
                    'asset_category_id' => $id,
                    'unit_name' => $realUnitName,
                    'is_active' => $isActive,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->route('manage-category')->with('success', 'Pengaturan akses submenu per unit berhasil diperbarui!');
    }

    // Fungsi untuk memproses edit kolom form
    public function fieldsUpdate(Request $request, $fieldId)
    {
        $field = \App\Models\AssetCategoryField::findOrFail($fieldId);
        
        $field->update([
            'name' => $request->name, // Nama / Label Kolom
            'field_type' => $request->field_type, // Teks, Angka, Tanggal, atau Dropdown
            'options' => $request->options, // Pilihan dropdown (jika ada)
            'is_required' => $request->has('is_required'), // Centang Wajib Diisi
            'show_in_table' => $request->has('show_in_table'), // Centang Tampil di Tabel
        ]);

        return back()->with('success', 'Spesifikasi kolom berhasil diperbarui.');
    }

    public function toggleShowInTable($fieldId)
    {
        $field = \App\Models\AssetCategoryField::findOrFail($fieldId);
        
        // Cara langsung ini kebal dari error $fillable Mass Assignment
        $field->show_in_table = !$field->show_in_table;
        $field->save();

        return response()->json([
            'success' => true, 
            'show_in_table' => $field->show_in_table
        ]);
    }
}