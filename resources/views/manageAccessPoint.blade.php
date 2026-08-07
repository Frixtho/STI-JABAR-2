@extends('layouts.app', ['title' => 'Manage Access Point — PLN Financial'])

@section('content')
{{-- x-data dinaikkan ke div paling luar agar state modal tertib dan reaktif --}}
<div class="min-h-screen flex bg-gray-50 dark:bg-gray-900" x-data="{ openTotalAssetModal: false }">

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
                <input type="text" placeholder="Cari unit atau data..."
                    class="w-full rounded-md border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 py-2 pl-10 pr-3 text-sm text-gray-700 dark:text-gray-200 placeholder-gray-400 focus:border-pln-700 focus:bg-white dark:focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-pln-700/20">
            </div>

            <div class="flex items-center gap-5 shrink-0">
                <button type="button" class="relative text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.85 23.85 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
                    <span class="absolute -top-0.5 -right-0.5 w-2 h-2 rounded-full bg-red-500"></span>
                </button>
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
        <main class="p-6 lg:p-10 space-y-6 w-full">

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

            <nav class="flex items-center gap-1.5 text-sm">
                <a href="{{ route('dashboard') }}" class="text-gray-400 dark:text-gray-500 hover:text-[#004A54] dark:hover:text-accent-400 transition-colors">Dashboard</a>
                <svg class="w-3.5 h-3.5 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
                <span class="font-semibold text-gray-700 dark:text-gray-200">Manage Asset: {{ $currentCategory->name ?? '' }}</span>
            </nav>

            {{-- Header Title & Action Buttons --}}
            <div class="flex items-start justify-between flex-wrap gap-4">
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider font-semibold">Manage Asset</p>
                    <h1 class="text-xl font-bold text-pln-800 dark:text-white tracking-wide">{{ $currentCategory->name ?? 'Daftar Aset' }}</h1>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    {{-- TOMBOL TOTAL ASSET (Alpine.js) --}}
                    <button type="button" @click="openTotalAssetModal = true" class="inline-flex items-center gap-1.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 px-3.5 py-2 rounded-md text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm cursor-pointer">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        Total Asset 
                    </button>

                    <a href="#" class="inline-flex items-center gap-1.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 px-3.5 py-2 rounded-md text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Ekspor Excel
                    </a>
                    <a href="{{ route('manage-access-point.create') }}" class="inline-flex items-center gap-2 bg-[#004A54] text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-[#00363d] transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah Access Point
                    </a>
                </div>
            </div>

            {{-- Filter & Cari Bar --}}
            <div class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4 shadow-sm">
                <form method="GET" action="{{ route('manage-access-point') }}" class="w-full flex flex-wrap items-center gap-3">
                    <div class="flex-1 min-w-[240px] relative">
                        <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="7" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="m20 20-3.5-3.5" />
                            </svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID, merk, model, SN, IP, MAC, lokasi..."
                            class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2 pl-9 pr-3 text-sm text-gray-700 dark:text-gray-200 placeholder-gray-400 focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                    </div>

                    <div>
                        <select name="kondisi" onchange="this.form.submit()" class="rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2 px-3 text-sm text-gray-700 dark:text-gray-200 focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                            <option value="">Semua kondisi</option>
                            <option value="baik" @selected(request('kondisi') == 'baik')>Baik</option>
                            <option value="rusak ringan" @selected(request('kondisi') == 'rusak ringan')>Rusak Ringan</option>
                            <option value="rusak berat" @selected(request('kondisi') == 'rusak berat')>Rusak Berat</option>
                        </select>
                    </div>

                    <div>
                        <select name="status_operasional" onchange="this.form.submit()" class="rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2 px-3 text-sm text-gray-700 dark:text-gray-200 focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                            <option value="">Semua status ops</option>
                            <option value="aktif" @selected(request('status_operasional') == 'aktif')>Aktif</option>
                            <option value="non-aktif" @selected(request('status_operasional') == 'non-aktif')>Non-Aktif</option>
                        </select>
                    </div>

                    <div>
                        <select name="kritikalitas" onchange="this.form.submit()" class="rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2 px-3 text-sm text-gray-700 dark:text-gray-200 focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                            <option value="">Semua kritikalitas</option>
                            <option value="penting" @selected(request('kritikalitas') == 'penting')>Penting</option>
                            <option value="sangat penting" @selected(request('kritikalitas') == 'sangat penting')>Sangat Penting</option>
                            <option value="biasa" @selected(request('kritikalitas') == 'biasa')>Biasa</option>
                        </select>
                    </div>

                    <div>
                        <select name="lokasi" onchange="this.form.submit()" class="rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2 px-3 text-sm text-gray-700 dark:text-gray-200 focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                            <option value="">Semua lokasi</option>
                            @foreach($lokasiList ?? [] as $lok)
                                <option value="{{ $lok }}" @selected(request('lokasi') == $lok)>{{ $lok }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="shrink-0">
                        <a href="{{ route('manage-access-point') }}" class="border-2 border-[#004A54] dark:border-accent-400 text-[#004A54] dark:text-accent-400 px-5 py-2 rounded-lg text-sm font-bold hover:bg-cyan-50/50 dark:hover:bg-gray-700 transition-all block text-center tracking-wide">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- ===================== TABLE ===================== --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50/75 dark:bg-gray-900/40 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                <th class="py-3 px-4">ID Aset</th>
                                <th class="py-3 px-4">Merk / Model</th>
                                <th class="py-3 px-4">Serial Number</th>
                                <th class="py-3 px-4">IP / MAC</th>
                                <th class="py-3 px-4">Lokasi</th>
                                <th class="py-3 px-4">Kondisi</th>
                                <th class="py-3 px-4">Ops</th>
                                <th class="py-3 px-4">Kritikalitas</th>
                                <th class="py-3 px-4">Garansi</th>
                                <th class="py-3 px-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            @forelse ($accessPoints ?? [] as $ap)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/40 transition-colors">
                                    <td class="py-3.5 px-4 font-semibold text-[#004A54] dark:text-accent-400 whitespace-nowrap">
                                        {{ $ap->id_aset ?? '-' }}
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="font-bold text-gray-800 dark:text-white">{{ $ap->merk ?? '-' }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $ap->model ?? '-' }}</div>
                                    </td>
                                    <td class="py-3.5 px-4 text-gray-700 dark:text-gray-300 font-mono text-xs">
                                        {{ $ap->serial_number ?? '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-xs font-mono text-gray-600 dark:text-gray-300">
                                        <div>{{ $ap->ip_address ?? '-' }}</div>
                                        <div class="text-gray-400 mt-0.5">{{ $ap->mac_address ?? '-' }}</div>
                                    </td>
                                    <td class="py-3.5 px-4 text-gray-700 dark:text-gray-300">
                                        <div>{{ $ap->lokasi_aset_saat_ini ?? '-' }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $ap->keterangan_lokasi ?? '-' }}</div>
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        @php
                                            $kondisi = strtolower($ap->status_kondisi ?? 'baik');
                                            $kondisiClass = match($kondisi) {
                                                'baik' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:border-emerald-800',
                                                'rusak ringan' => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-800',
                                                default => 'bg-red-50 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $kondisiClass }}">
                                            {{ $ap->status_kondisi ?? 'baik' }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        @php
                                            $ops = strtolower($ap->status_operasional ?? 'aktif');
                                            $opsClass = ($ops === 'aktif') 
                                                ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:border-emerald-800' 
                                                : 'bg-gray-100 text-gray-600 border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $opsClass }}">
                                            {{ $ap->status_operasional ?? 'aktif' }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        @php
                                            $krit = strtolower($ap->tingkat_kritikalitas ?? 'penting');
                                            $kritClass = match($krit) {
                                                'sangat penting' => 'bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-900/30 dark:text-purple-300 dark:border-purple-800',
                                                'penting' => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-800',
                                                default => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $kritClass }}">
                                            {{ $ap->tingkat_kritikalitas ?? 'penting' }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-xs text-gray-600 dark:text-gray-400 whitespace-nowrap">
                                        @if(!empty($ap->masa_berlaku_garansi))
                                            {{ \Carbon\Carbon::parse($ap->masa_berlaku_garansi)->format('d M Y') }}
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">Tidak dicatat</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                        <div class="inline-flex items-center justify-center gap-1.5">
                                            @if (!empty($ap->latitude) && !empty($ap->longitude))
                                                <button type="button" class="distance-btn text-gray-400 dark:text-gray-500 hover:text-[#004A54] dark:hover:text-accent-400 p-1.5 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                                                    data-url="#" title="Hitung Jarak">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                                    </svg>
                                                </button>
                                            @endif
                                            
                                            <a href="{{ route('manage-access-point.edit', $ap) }}" class="text-gray-400 dark:text-gray-500 hover:text-[#004A54] dark:hover:text-accent-400 p-1.5 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" title="Edit">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </a>

                                            <form action="{{ route('manage-access-point.destroy', $ap) }}" method="POST" onsubmit="return confirm('Hapus access point {{ $ap->id_aset ?? '' }}?')" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-400 dark:text-gray-500 hover:text-red-600 dark:hover:text-red-400 p-1.5 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150 focus:outline-none" title="Hapus">
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
                                    <td colspan="10" class="p-12 text-center text-gray-400 dark:text-gray-500">
                                        <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                        Belum ada data access point yang cocok ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Footer Pagination --}}
                @if(isset($accessPoints) && method_exists($accessPoints, 'links'))
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                            Menampilkan {{ $accessPoints->firstItem() ?? 0 }} - {{ $accessPoints->lastItem() ?? 0 }} dari {{ $accessPoints->total() }} aset
                        </div>
                        <div class="laravel-pagination">
                            {{ $accessPoints->onEachSide(1)->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </main>
    </div>

    {{-- ===================== MODAL TOTAL ASSET ===================== --}}
    <div x-show="openTotalAssetModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-xs transition-opacity" @click="openTotalAssetModal = false"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-xl border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 dark:border-gray-700/60">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white" id="modal-title">
                        Ringkasan Total Asset Access Point
                    </h3>
                    <button @click="openTotalAssetModal = false" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-4 text-sm text-gray-600 dark:text-gray-300">
                    <div class="rounded-xl border border-cyan-100 dark:border-[#004A54]/40 bg-cyan-50/50 dark:bg-gray-900/40 p-4 shadow-xs">
                        <p class="font-bold text-[#004A54] dark:text-accent-400 text-xs sm:text-sm">
                            Informasi Keseluruhan Data Perangkat
                        </p>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                            Berikut adalah rekapitulasi data aset perangkat Access Point yang terdaftar pada sistem database PLN Financial.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="border border-gray-200/80 dark:border-gray-700 rounded-xl p-3.5 bg-white dark:bg-gray-800/60 shadow-xs">
                            <div class="text-xl font-bold text-[#004A54] dark:text-accent-400">
                                {{ \App\Models\AccessPoint::count() }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Total Access Point</div>
                        </div>
                        <div class="border border-gray-200/80 dark:border-gray-700 rounded-xl p-3.5 bg-white dark:bg-gray-800/60 shadow-xs">
                            <div class="text-xl font-bold text-emerald-600 dark:text-emerald-400">
                                {{ \App\Models\AccessPoint::where('status_operasional', 'aktif')->count() }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Status Aktif</div>
                        </div>
                        <div class="border border-gray-200/80 dark:border-gray-700 rounded-xl p-3.5 bg-white dark:bg-gray-800/60 shadow-xs">
                            <div class="text-xl font-bold text-amber-600 dark:text-amber-400">
                                {{ \App\Models\AccessPoint::whereIn('status_kondisi', ['rusak ringan', 'rusak berat'])->count() }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Perlu Perhatian</div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50/75 dark:bg-gray-900/40 px-6 py-4 flex items-center justify-end border-t border-gray-100 dark:border-gray-700/60">
                    <button @click="openTotalAssetModal = false" type="button" class="px-4 py-2 text-xs font-semibold text-white bg-[#004A54] hover:bg-[#00363d] rounded-lg transition-all shadow-sm cursor-pointer">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection