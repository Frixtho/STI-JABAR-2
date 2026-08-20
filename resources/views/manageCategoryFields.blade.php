@extends('layouts.app', ['title' => 'Atur Spesifikasi: ' . $category->name])

@section('content')
<main class="p-6 space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('manage-category') }}" class="p-2 bg-white dark:bg-gray-800 rounded-full shadow-sm hover:bg-gray-50 border border-gray-200">
            <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Rancang Form: {{ $category->name }}</h1>
            <p class="text-sm text-gray-500">Tentukan atribut atau kolom apa saja yang perlu diisi saat menambahkan aset {{ $category->name }}.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-100 text-green-700 rounded-lg font-medium">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-100 text-red-700 rounded-lg font-medium">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- KOLOM KIRI: FORM TAMBAH FIELD --}}
        <div class="md:col-span-1">
            <form action="{{ route('manage-category.fields.store', $category->id) }}" method="POST" class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 shadow-sm space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold mb-1">Nama Kolom (Label)</label>
                    <input type="text" name="name" required placeholder="Cth: IP Address, Plat Nomor..." class="w-full rounded-md border-gray-300 py-2 px-3 focus:ring-[#004A54]">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Tipe Inputan</label>
                    <select name="field_type" id="fieldType" required class="w-full rounded-md border-gray-300 py-2 px-3 focus:ring-[#004A54]" onchange="document.getElementById('optionsWrapper').style.display = this.value === 'select' ? 'block' : 'none'">
                        <option value="text">Teks Singkat</option>
                        <option value="number">Angka</option>
                        <option value="date">Tanggal</option>
                        <option value="select">Dropdown (Pilihan)</option>
                    </select>
                </div>

                <div id="optionsWrapper" style="display: none;">
                    <label class="block text-sm font-semibold mb-1">Daftar Pilihan</label>
                    <input type="text" name="options" placeholder="Cth: Aktif, Rusak, Perbaikan" class="w-full rounded-md border-gray-300 py-2 px-3">
                    <p class="text-[11px] text-gray-500 mt-1">Pisahkan tiap pilihan dengan tanda koma (,)</p>
                </div>

                <div class="flex gap-4">
                    <label class="flex items-center gap-2 text-sm cursor-pointer">
                        <input type="checkbox" name="is_required" class="rounded text-[#004A54] focus:ring-[#004A54]"> Wajib Diisi
                    </label>
                    <label class="flex items-center gap-2 text-sm cursor-pointer">
                        <input type="checkbox" name="show_in_table" checked class="rounded text-[#004A54] focus:ring-[#004A54]"> Tampil di Tabel
                    </label>
                </div>

                <button type="submit" class="w-full bg-[#004A54] text-white px-4 py-2 rounded-md font-semibold hover:bg-[#00363d]">+ Tambah Kolom</button>
            </form>
        </div>

        {{-- KOLOM KANAN: PREVIEW DAFTAR FIELD --}}
        <div class="md:col-span-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="font-bold text-gray-700">Daftar Spesifikasi {{ $category->name }}</h3>
            </div>
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-500 border-b">
                    <tr>
                        <th class="px-6 py-3">Nama Kolom</th>
                        <th class="px-6 py-3">Tipe</th>
                        <th class="px-6 py-3 text-center">Atribut</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    
                    {{-- ===================== 1. ATRIBUT UMUM (BAWAAN SISTEM) ===================== --}}
                    <tr class="bg-gray-50/50 dark:bg-gray-800/50">
                        <td colspan="4" class="px-4 py-2 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-100 dark:bg-gray-800">
                            Atribut Umum (Bawaan Sistem)
                        </td>
                    </tr>

                    @php
                        // Daftar field bawaan yang selalu ada di tabel assets utama
                        $defaultFields = [
                            ['name' => 'ID Aset', 'type' => 'Teks Singkat', 'required' => true],
                            ['name' => 'Tanggal Mulai Aktif / Perolehan', 'type' => 'Tanggal', 'required' => false],
                            ['name' => 'Status Kepemilikan', 'type' => 'Pilihan (Dropdown)', 'required' => false],
                            ['name' => 'Ket. Status Kepemilikan', 'type' => 'Teks Singkat', 'required' => false],
                            ['name' => 'Status Kondisi Aset', 'type' => 'Pilihan (Dropdown)', 'required' => true],
                            ['name' => 'Status Operasional', 'type' => 'Pilihan (Dropdown)', 'required' => true],
                            ['name' => 'Tingkat Kritikalitas', 'type' => 'Pilihan (Dropdown)', 'required' => true],
                            ['name' => 'Klasifikasi Keamanan', 'type' => 'Pilihan (Dropdown)', 'required' => false],
                            ['name' => 'Lokasi Aset Saat Ini (Kode)', 'type' => 'Pilihan (Dropdown)', 'required' => true],
                            ['name' => 'Tanggal Pemeriksaan Terakhir', 'type' => 'Tanggal', 'required' => false],
                            ['name' => 'Deskripsi / Peran Aset', 'type' => 'Teks Panjang', 'required' => false],
                            ['name' => 'Keterangan Lokasi Aset', 'type' => 'Teks Panjang', 'required' => false],
                            ['name' => 'PIC Pencatat', 'type' => 'Teks Singkat', 'required' => true],
                            ['name' => 'Bidang Pencatat Aset', 'type' => 'Teks Singkat', 'required' => false],
                        ];
                    @endphp

                    {{-- INI ADALAH FOREACH UNTUK ATRIBUT BAWAAN --}}
                    @foreach($defaultFields as $df)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                        <td class="px-4 py-3 font-medium text-gray-700 dark:text-gray-300">
                            {{ $df['name'] }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                            {{ $df['type'] }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium {{ $df['required'] ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                                {{ $df['required'] ? 'Wajib Diisi' : 'Opsional' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            <span class="inline-flex items-center gap-1 text-xs text-gray-400 dark:text-gray-500 italic" title="Atribut bawaan tidak dapat dihapus">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Permanen
                            </span>
                        </td>
                    </tr>
                    @endforeach

                    {{-- ===================== 2. ATRIBUT SPESIFIKASI TAMBAHAN ===================== --}}
                    <tr class="bg-gray-50/50 dark:bg-gray-800/50">
                        <td colspan="4" class="px-4 py-2 text-xs font-bold text-[#004A54] dark:text-accent-400 uppercase tracking-wider bg-cyan-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                            Spesifikasi Tambahan (Dinamis)
                        </td>
                    </tr>

                    {{-- INI ADALAH FORELSE UNTUK FIELD DINAMIS DARI DATABASE --}}
                    @forelse(\App\Models\AssetCategoryField::where('asset_category_id', $category->id)->get() as $field)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                        <td class="px-4 py-3 font-semibold text-[#004A54] dark:text-white">
                            {{ $field->field_label }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                            {{ $field->field_type }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex gap-2">
                                @if($field->is_required)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-red-100 text-red-700">Wajib Diisi</span>
                                @endif
                                @if($field->show_in_table)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-blue-100 text-blue-700">Tampil di Tabel</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            <form action="{{ route('manage-category.fields.destroy', $field->id) }}" method="POST" onsubmit="return confirm('Hapus spesifikasi ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-semibold text-xs transition-colors">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-400 dark:text-gray-500 text-sm">
                            Belum ada atribut spesifikasi tambahan yang diatur.<br>
                            <span class="text-xs">Gunakan form di sebelah kiri untuk menambahkan spesifikasi khusus untuk aset ini.</span>
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection