@extends('layouts.app', ['title' => 'Atur Unit: ' . $category->name])

@section('content')
<main class="p-6 lg:p-10 space-y-6 w-full">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-sm">
        <a href="{{ route('manage-category') }}" class="text-gray-400 dark:text-gray-500 hover:text-[#004A54] dark:hover:text-accent-400 transition-colors">
            Manage Kategori Aset
        </a>
        <svg class="w-3.5 h-3.5 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
        </svg>
        <span class="font-semibold text-gray-700 dark:text-gray-200">
            Atur Akses Unit: {{ $category->name }}
        </span>
    </nav>

    {{-- Header --}}
    <div>
        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
            Unit Access Control
        </p>
        <h1 class="text-2xl font-bold text-pln-800 dark:text-white">
            Pengaturan Submenu "{{ $category->name }}" per Unit
        </h1>
        <p class="mt-1 text-sm text-gray-400 dark:text-gray-500">
            Tentukan unit mana saja yang diizinkan untuk melihat atau menonaktifkan submenu ini di dalam sistem.
        </p>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-100 text-green-700 rounded-lg font-medium text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form Pengaturan Unit --}}
    <form action="{{ route('manage-category.unit-settings.save', $category->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PATCH')

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex justify-between items-center">
                <h3 class="font-bold text-gray-700 dark:text-gray-200">Daftar Unit / Departemen</h3>
            </div>

            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 dark:bg-gray-900/30 text-gray-500 border-b border-gray-200 dark:border-gray-700 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3 font-bold">Nama Unit / Departemen</th>
                        <th class="px-6 py-3 font-bold text-center">Status Submenu untuk Unit Ini</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($units as $unitName)
                        @php
                            // Ambil setting khusus unit ini dari tabel category_unit_settings
                            $existingSetting = \DB::table('category_unit_settings')
                                ->where('asset_category_id', $category->id)
                                ->where('unit_name', $unitName)
                                ->first();

                            // 3. BOOLEAN AMAN POSTGRESQL:
                            // PostgreSQL bisa mengembalikan boolean sebagai 1/0, true/false, atau 't'/'f'.
                            // Kita pakai in_array agar semua kemungkinan "Aktif" tertangkap sempurna.
                            if ($existingSetting) {
                                $isActive = in_array($existingSetting->is_active, [1, '1', true, 'true', 't', 'T']);
                            } else {
                                $isActive = in_array($category->is_active, [1, '1', true, 'true', 't', 'T']);
                            }
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                            <td class="px-6 py-4 font-semibold text-gray-800 dark:text-white">
                                {{ $unitName }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    {{-- Trik Hidden Input: Mengirim nilai 0 jika switch dimatikan (unchecked) --}}
                                    <input type="hidden" name="settings[{{ $unitName }}]" value="0">
                                    
                                    {{-- Checkbox Asli untuk Switch --}}
                                    <input type="checkbox" name="settings[{{ $unitName }}]" value="1" class="sr-only peer" {{ $isActive ? 'checked' : '' }}>
                                    
                                    {{-- Desain Switch Toggle --}}
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-[#004A54]"></div>
                                </label>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('manage-category') }}" class="px-5 py-2.5 border border-gray-300 dark:border-gray-600 rounded-md text-sm text-gray-700 dark:text-gray-300 font-semibold hover:bg-gray-50 transition-colors">
                Kembali
            </a>
            <button type="submit" class="bg-[#004A54] text-white px-6 py-2.5 rounded-md text-sm font-semibold hover:bg-[#00363d] transition-colors shadow-sm">
                Simpan Pengaturan Unit
            </button>
        </div>
    </form>
</main>
@endsection