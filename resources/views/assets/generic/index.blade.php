@extends('layouts.app', ['title' => 'Manage ' . $currentCategory->name . ' — PLN Asset Management'])

@section('content')
<div class="min-h-screen flex bg-gray-50 dark:bg-gray-900">
    <div class="flex-1 min-w-0">

        {{-- ===================== TOP BAR ===================== --}}
        <header class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 px-6 py-4 flex items-center justify-between gap-4">
            <div class="relative w-full max-w-sm">
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <circle cx="11" cy="11" r="7" stroke-linecap="round" stroke-linejoin="round" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="m20 20-3.5-3.5" />
                    </svg>
                </span>
                <input type="text" placeholder="Cari data aset..."
                    class="w-full rounded-md border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 py-2 pl-10 pr-3 text-sm text-gray-700 dark:text-gray-200 placeholder-gray-400 focus:border-[#004A54] focus:bg-white dark:focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-[#004A54]/20">
            </div>

            <div class="flex items-center gap-5 shrink-0">
                <div class="flex items-center gap-2">
                    <div class="text-right leading-tight">
                        <p class="text-sm font-semibold text-pln-800 dark:text-white">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] font-semibold uppercase tracking-wide {{ strcasecmp(auth()->user()->role, 'Admin') === 0 ? 'text-accent-500' : 'text-gray-400 dark:text-gray-500' }}">
                            {{ auth()->user()->role }}
                        </p>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-pln-800 text-white flex items-center justify-center font-bold text-xs uppercase shrink-0">
                        {{ implode('', array_map(fn($w) => $w[0] ?? '', array_slice(explode(' ', auth()->user()->name), 0, 2))) }}
                    </div>
                </div>
            </div>
        </header>

        {{-- ===================== MAIN CONTENT ===================== --}}
        <main class="p-6 space-y-5">

            @if (session('success'))
                <div class="rounded-md border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/30 px-4 py-3 text-sm text-green-700 dark:text-green-400">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Header Title & Action Button --}}
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-bold text-pln-800 dark:text-white tracking-wide">Manage {{ $currentCategory->name }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola data inventaris untuk aset {{ $currentCategory->name }} secara dinamis.</p>
                </div>
                
                <div class="flex items-center gap-3">
                    {{-- Tombol Import --}}
                    <a href="{{ route('manage-asset.generic.import.form', $currentCategory->slug) }}" class="inline-flex items-center justify-center bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                        </svg>
                        Import File CSV
                    </a>

                    {{-- Tombol Tambah Baru --}}
                    <a href="{{ route('manage-asset.generic.create', $currentCategory->slug) }}" class="inline-flex items-center justify-center bg-[#004A54] text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-[#00363d] transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah {{ $currentCategory->name }}
                    </a>
                </div>
            </div>

            {{-- Filter & Cari --}}
            <div class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4 shadow-sm">
                <form action="{{ route('manage-asset.generic.index', $currentCategory->slug) }}" method="GET" class="w-full flex items-center gap-4">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h12M4 18h8" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau kode aset..."
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 pl-9 pr-4 text-sm text-gray-700 dark:text-gray-200 placeholder-gray-400 focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                    </div>
                    <div class="shrink-0 flex gap-2">
                        <button type="submit" class="bg-[#004A54] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-[#00363d] transition-colors">
                            Cari
                        </button>
                        <a href="{{ route('manage-asset.generic.index', $currentCategory->slug) }}" class="border-2 border-[#004A54] dark:border-accent-400 text-[#004A54] dark:text-accent-400 px-4 py-2 rounded-lg text-sm font-bold hover:bg-cyan-50/50 dark:hover:bg-gray-700 transition-all text-center tracking-wide">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- ===================== TABLE DINAMIS ===================== --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
                
                @php
                    // Ambil field-field yang diatur 'show_in_table' = true
                    $tableColumns = $currentCategory->fields->where('show_in_table', true);
                @endphp

                {{-- Wadah khusus tabel yang memiliki scrollbar horizontal --}}
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-gray-50 dark:bg-gray-900/40 border-b border-gray-200 dark:border-gray-700 text-xs uppercase tracking-wider text-gray-500">
                            <tr>
                                <th class="px-6 py-4 font-bold">Nama Aset</th>
                                <th class="px-6 py-4 font-bold">Kode / Functloc</th>
                                
                                {{-- Cetak Header Kolom Dinamis --}}
                                @foreach($tableColumns as $col)
                                    <th class="px-6 py-4 font-bold">{{ $col->name }}</th>
                                @endforeach

                                <th class="px-6 py-4 font-bold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($assets as $asset)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                                    <td class="px-6 py-3 font-semibold text-gray-800 dark:text-white">{{ $asset->name }}</td>
                                    <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ $asset->code ?? '-' }}</td>
                                    
                                    {{-- Cetak Isi Data Kolom Dinamis (Ambil dari JSON) --}}
                                    @foreach($tableColumns as $col)
                                        <td class="px-6 py-3 text-gray-600 dark:text-gray-300">
                                            {{ $asset->specifications[$col->field_key] ?? '-' }}
                                        </td>
                                    @endforeach

                                    <td class="px-6 py-3 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            
                                            {{-- Tombol Edit dengan Kotak Border --}}
                                            <a href="{{ route('manage-asset.generic.edit', ['category' => $currentCategory->slug, 'id' => $asset->id]) }}" 
                                               class="w-8 h-8 flex items-center justify-center border border-gray-200 rounded-md text-gray-500 hover:border-[#004A54] hover:text-[#004A54] hover:bg-gray-50 transition-all shadow-sm" title="Edit">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </a>

                                            {{-- Tombol Hapus dengan Kotak Border --}}
                                            <form action="{{ route('manage-asset.generic.destroy', ['category' => $currentCategory->slug, 'id' => $asset->id]) }}" method="POST" onsubmit="return confirm('Hapus aset ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="w-8 h-8 flex items-center justify-center border border-gray-200 rounded-md text-gray-500 hover:border-red-500 hover:text-red-500 hover:bg-red-50 transition-all shadow-sm" title="Hapus">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                            
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ 3 + $tableColumns->count() }}" class="px-6 py-10 text-center text-gray-500">
                                        Belum ada data. Silakan tambah aset baru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination berada di luar wadah scrollbar sehingga rapi di bawah card --}}
                @if($assets->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $assets->links() }}
                </div>
                @endif
            </div>

        </main>
    </div>
</div>
@endsection