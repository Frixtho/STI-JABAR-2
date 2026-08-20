@extends('layouts.app', ['title' => 'Manage Kategori Aset'])

@section('content')
<main class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Manage Kategori Aset</h1>
            <p class="text-sm text-gray-500">Tambah, ubah, atau hapus kategori (submenu) aset di sini.</p>
        </div>
    </div>

    {{-- Alert Notifikasi --}}
    @if(session('success'))
        <div class="p-4 bg-green-100 text-green-700 rounded-lg font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form Tambah Kategori (Submenu Baru) --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <form action="{{ route('manage-category.store') }}" method="POST" class="flex items-end gap-4">
            @csrf
            <div class="flex-1">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nama Kategori / Submenu Baru</label>
                <input type="text" name="name" required placeholder="Contoh: VLAN..." 
                    class="w-full rounded-md border border-gray-300 px-4 py-2 focus:ring-pln-700 focus:border-pln-700">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="bg-[#004A54] text-white px-6 py-2 rounded-md font-semibold hover:bg-[#00363d] transition">
                + Tambah Submenu
            </button>
        </form>
    </div>

    {{-- Tabel Daftar Kategori yang sudah ada --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                <tr>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase">Nama Submenu</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($categories as $category)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-6 py-4 font-semibold text-gray-800 dark:text-gray-200">{{ $category->name }}</td>
                    
                    {{-- Kolom Status --}}
                    <td class="px-6 py-4">
                        @if($category->is_active)
                            <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-md">Aktif</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-md">Nonaktif</span>
                        @endif
                    </td>
                    
                    {{-- Kolom Aksi --}}
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-4">
                            
                            {{-- Tombol Atur Per Unit --}}
                            <a href="{{ route('manage-category.unit-settings', $category->id) }}" class="text-[#004A54] hover:text-[#00363d] font-semibold text-sm transition-colors">
                                Atur per Unit
                            </a>
                            
                            {{-- Garis Pemisah (Opsional, agar rapi seperti di gambar Anda) --}}
                            <span class="w-px h-4 bg-gray-300"></span>

                            {{-- Tombol Atur Form --}}
                            <a href="{{ route('manage-category.fields', $category->id) }}" class="text-gray-600 hover:text-gray-900 font-semibold text-sm transition-colors flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Atur Form
                            </a>

                            {{-- Garis Pemisah --}}
                            <span class="w-px h-4 bg-gray-300"></span>

                            {{-- Tombol Hapus --}}
                            <form action="{{ route('manage-category.destroy', $category->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus kategori ini? Semua aset di dalamnya juga akan terhapus.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-semibold text-sm transition-colors">
                                    Hapus
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-8 text-center text-gray-500">Belum ada kategori aset tambahan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>
@endsection