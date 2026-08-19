@extends('layouts.app', ['title' => ($line->name ?? 'Detail Jalur') . ' — PLN Financial'])

@section('content')
<div class="min-h-screen flex bg-gray-50 dark:bg-gray-900">

    {{-- ===================== MAIN CONTENT ===================== --}}
    <div class="flex-1 min-w-0">

        {{-- Top bar --}}
        <header class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 px-6 py-4 flex items-center justify-between gap-4">
            <div class="relative w-full max-w-sm">
                <input type="text" disabled placeholder="Mode Rincian File / Jalur..."
                    class="w-full rounded-md border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 py-2 px-3 text-sm text-gray-400 cursor-not-allowed">
            </div>

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
        </header>

        {{-- Content Area --}}
        <main class="p-6 space-y-5">

            @if (session('success'))
                <div class="rounded-md border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/30 px-4 py-3 text-sm text-green-700 dark:text-green-400">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-1.5 text-sm">
                <a href="{{ route('manage-asset') }}" class="text-gray-400 dark:text-gray-500 hover:text-[#004A54] dark:hover:text-accent-400 transition-colors">Manage Asset</a>
                <svg class="w-3.5 h-3.5 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
                <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $line->name ?? '-' }}</span>
            </nav>

            <div class="flex items-center justify-between">
                <h1 class="text-xl font-bold text-pln-800 dark:text-white">{{ $line->name ?? '-' }}</h1>
                <a href="{{ route('manage-asset') }}" class="text-sm px-4 py-1.5 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">Kembali</a>
            </div>

            {{-- Stat cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4 shadow-sm">
                    <p class="text-xs text-gray-400 dark:text-gray-500 font-semibold uppercase tracking-wider">Tegangan</p>
                    <p class="text-lg font-bold text-pln-800 dark:text-white mt-1">{{ $line->tegangan ?? '150 kV' }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4 shadow-sm">
                    <p class="text-xs text-gray-400 dark:text-gray-500 font-semibold uppercase tracking-wider">GI Awal → Akhir</p>
                    <p class="text-sm font-bold text-pln-800 dark:text-white mt-2 truncate" title="{{ $line->gi_awal_name ?? '—' }} → {{ $line->gi_akhir_name ?? '—' }}">
                        {{ $line->gi_awal_name ?? 'N/A' }} → {{ $line->gi_akhir_name ?? 'N/A' }}
                    </p>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4 shadow-sm">
                    <p class="text-xs text-gray-400 dark:text-gray-500 font-semibold uppercase tracking-wider">Total Tower</p>
                    <p class="text-lg font-bold text-pln-800 dark:text-white mt-1">{{ $totalTowers ?? ($towers->total() ?? 0) }} Titik</p>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4 shadow-sm">
                    <p class="text-xs text-gray-400 dark:text-gray-500 font-semibold uppercase tracking-wider">Panjang Jalur</p>
                    <p class="text-lg font-bold text-pln-800 dark:text-white mt-1">{{ number_format($pathLengthKm ?? 0, 2) }} KM</p>
                </div>
            </div>

            {{-- ===================== TABLE RINCIAN TOWER ===================== --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead class="bg-gray-50 dark:bg-gray-900/40 border-b border-gray-200 dark:border-gray-700 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            <tr>
                                <th class="px-5 py-4">Urutan (#)</th>
                                <th class="px-5 py-4">Nama Tower</th>
                                <th class="px-5 py-4">Functloc</th>
                                <th class="px-5 py-4">Koordinat (Lat / Lng)</th>
                                <th class="px-5 py-4 text-center">Jarak Dgn Sblmnya</th>
                                <th class="px-5 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            @forelse ($towers as $tower)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/40 transition-colors">
                                    <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 font-medium">
                                        T-{{ $tower->tower_number }}
                                    </td>
                                    <td class="px-5 py-3.5 font-semibold text-gray-800 dark:text-white">
                                        {{ $tower->name ?? '-' }}
                                    </td>
                                    <td class="px-5 py-3.5 text-xs font-mono text-gray-500 dark:text-gray-400">
                                        {{ $tower->functloc ?? '-' }}
                                    </td>
                                    <td class="px-5 py-3.5 text-xs text-gray-600 dark:text-gray-300">
                                        <div>{{ $tower->latitude ?? '-' }}</div>
                                        <div>{{ $tower->longitude ?? '-' }}</div>
                                    </td>
                                    <td class="px-5 py-3.5 text-center">
                                        {{-- Menampilkan Jarak Antar Tower --}}
                                        @if(isset($tower->jarak_antar_tower) && $tower->jarak_antar_tower > 0)
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                                {{ $tower->jarak_antar_tower }} m
                                            </span>
                                        @else
                                            <span class="text-xs italic text-gray-400">Awal Line / N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('manage-asset.tower.edit', $tower->id) }}" class="text-gray-400 dark:text-gray-500 hover:text-[#004A54] dark:hover:text-accent-400 p-1.5 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('manage-asset.tower.destroy', $tower->id) }}" method="POST" onsubmit="return confirm('Hapus spesifik tower {{ $tower->name }} dari jalur ini?')" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-400 dark:text-gray-500 hover:text-red-600 dark:hover:text-red-400 p-1.5 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors focus:outline-none">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-12 text-center text-gray-400 dark:text-gray-500 bg-white dark:bg-gray-800">
                                        Belum ada tower pada jalur ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(isset($towers) && method_exists($towers, 'links'))
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40">
                        {{ $towers->onEachSide(1)->links() }}
                    </div>
                @endif
            </div>

        </main>
    </div>
</div>
@endsection