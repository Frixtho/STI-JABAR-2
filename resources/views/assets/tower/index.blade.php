@extends('layouts.app', ['title' => 'Manage ' . ($currentCategory->name ?? 'Asset') . ' — PLN Financial'])

@section('content')
<div class="min-h-screen flex bg-gray-50 dark:bg-gray-900">

    {{-- ===================== MAIN CONTENT ===================== --}}
    <div class="flex-1 min-w-0">

        {{-- Top bar --}}
        <header class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 px-6 py-4 flex items-center justify-between gap-4">
            <div class="relative w-full max-w-sm">
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <circle cx="11" cy="11" r="7" stroke-linecap="round" stroke-linejoin="round" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="m20 20-3.5-3.5" />
                    </svg>
                </span>
                <input type="text" placeholder="Cari data jalur..."
                    class="w-full rounded-md border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 py-2 pl-10 pr-3 text-sm text-gray-700 dark:text-gray-200 placeholder-gray-400 focus:border-pln-700 focus:bg-white dark:focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-pln-700/20">
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

        {{-- Content Area --}}
        <main class="p-6 space-y-5">

            @if (session('success'))
                <div class="rounded-md border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/30 px-4 py-3 text-sm text-green-700 dark:text-green-400">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="rounded-md border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/30 px-4 py-3 text-sm text-red-700 dark:text-red-400">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-1.5 text-sm">
                <a href="{{ route('dashboard') }}" class="text-gray-400 dark:text-gray-500 hover:text-[#004A54] dark:hover:text-accent-400 transition-colors">Dashboard</a>
                <svg class="w-3.5 h-3.5 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
                <span class="font-semibold text-gray-700 dark:text-gray-200">Manage Asset: {{ $currentCategory->name ?? 'Tower SUTT' }}</span>
            </nav>

            {{-- Header Title & Action Buttons --}}
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider font-semibold">Manage Asset</p>
                    <h1 class="text-xl font-bold text-pln-800 dark:text-white tracking-wide">Daftar Jalur / File Data Tower</h1>
                </div>
                <div class="flex items-center gap-2">
                    {{-- Tombol Import (Diarahkan ke fungsi import tower) --}}
                    <a href="{{ route('manage-asset.tower.import.form') ?? '#' }}" class="inline-flex items-center gap-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                        </svg>
                        Import File CSV
                    </a>
                </div>
            </div>

            {{-- Filter & Cari --}}
            <div class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4 shadow-sm">
                <form method="GET" action="{{ route('manage-asset') }}" class="w-full flex flex-row flex-wrap items-center gap-4">
                    <div class="flex-1 min-w-[200px] relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h12M4 18h8" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama jalur / nama file..."
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 pl-9 pr-4 text-sm text-gray-700 dark:text-gray-200 placeholder-gray-400 focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54] transition-colors">
                    </div>
                    <div class="shrink-0">
                        <a href="{{ route('manage-asset') }}" class="border-2 border-[#004A54] dark:border-accent-400 text-[#004A54] dark:text-accent-400 px-5 py-2 rounded-lg text-sm font-bold hover:bg-cyan-50/50 dark:hover:bg-gray-700 transition-all block text-center tracking-wide">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- ===================== TABLE JALUR SUTT (FILE UTAMA) ===================== --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead class="bg-gray-50 dark:bg-gray-900/40 border-b border-gray-200 dark:border-gray-700 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3">Nama Jalur / File Import</th>
                                <th class="px-6 py-3">Tegangan</th>
                                <th class="px-6 py-3 text-center">Jumlah Tower</th>
                                <th class="px-6 py-3 text-center">Panjang (KM)</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($assets ?? [] as $asset)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/40 transition-colors text-sm text-gray-700 dark:text-gray-300">
                                    <td class="px-6 py-3.5">
                                        <div class="font-semibold text-[#004A54] dark:text-accent-400">{{ $asset->name ?? '-' }}</div>
                                        <div class="text-[10px] text-gray-400">{{ $asset->functloc ?? 'File CSV' }}</div>
                                    </td>
                                    <td class="px-6 py-3.5">{{ $asset->tegangan ?? '150 kV' }}</td>
                                    <td class="px-6 py-3.5 text-center font-medium">{{ $asset->jumlah_tower ?? 0 }} Titik</td>
                                    <td class="px-6 py-3.5 text-center font-medium">{{ $asset->panjang_km ?? 0 }} KM</td>
                                    <td class="px-6 py-3.5 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            {{-- Tombol Lihat Isi Tower di Dalam Jalur --}}
                                            <a href="{{ route('manage-asset.show', $asset->id) }}" class="text-[#004A54] hover:text-cyan-700 dark:text-accent-400 font-semibold text-xs transition-colors">
                                                Lihat Tower &rarr;
                                            </a>
                                            <form action="{{ route('manage-asset.destroy', $asset->id) }}" method="POST" onsubmit="return confirm('Hapus jalur ini beserta seluruh data towernya?');" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-semibold">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-12 text-center text-gray-400 dark:text-gray-500 bg-white dark:bg-gray-800">
                                        Belum ada data file jalur / tower yang diimport.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(isset($assets) && method_exists($assets, 'links'))
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40">
                        {{ $assets->onEachSide(1)->links() }}
                    </div>
                @endif
            </div>

        </main>
    </div>
</div>
@endsection