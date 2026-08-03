@extends('layouts.app', ['title' => 'Manage Unit — PLN Financial'])

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
                <span class="font-semibold text-gray-700 dark:text-gray-200">Manage Unit</span>
            </nav>

            {{-- Header Title & Action Buttons --}}
            <div class="flex items-center justify-between flex-wrap gap-3">
                <h1 class="text-lg font-bold text-pln-800 dark:text-white tracking-wide">Manage Unit</h1>
                <div class="flex items-center gap-2">
                    <a href="{{ route('manage-unit.import.form') }}" class="inline-flex items-center gap-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                        </svg>
                        Import Data
                    </a>
                    <a href="{{ route('manage-unit.create') }}" class="inline-flex items-center gap-2 bg-[#004A54] text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-[#00363d] transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah Unit
                    </a>
                </div>
            </div>

            {{-- Filter & Cari --}}
            <div class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4 shadow-sm">
                <form method="GET" action="{{ route('manage-unit') }}" class="w-full flex flex-row flex-wrap items-center gap-4">

                    <div class="flex-1 min-w-[200px] relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h12M4 18h8" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama unit..."
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 pl-9 pr-4 text-sm text-gray-700 dark:text-gray-200 placeholder-gray-400 focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54] transition-colors">
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 whitespace-nowrap">Level:</label>
                        <div class="relative w-32">
                            <select name="level" onchange="this.form.submit()" class="w-full appearance-none rounded-lg border border-gray-300 dark:border-gray-600 py-2 pl-3 pr-7 text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54] cursor-pointer">
                                <option value="">Semua Level</option>
                                <option value="1" @selected(request('level') == '1')>Lvl 1 — UIT</option>
                                <option value="2" @selected(request('level') == '2')>Lvl 2 — UPT</option>
                                <option value="3" @selected(request('level') == '3')>Lvl 3 — ULTG</option>
                                <option value="4" @selected(request('level') == '4')>Lvl 4 — GI</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-gray-400">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                    </div>

                    <div class="shrink-0">
                        <a href="{{ route('manage-unit') }}" class="border-2 border-[#004A54] dark:border-accent-400 text-[#004A54] dark:text-accent-400 px-5 py-1.5 rounded-lg text-sm font-bold hover:bg-cyan-50/50 dark:hover:bg-gray-700 transition-all block text-center tracking-wide">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- ===================== TABLE ===================== --}}
            {{-- ===================== TABLE ===================== --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">

            {{-- Header Tabel dengan 7 Kolom sesuai Mockup --}}
            <div class="grid grid-cols-12 bg-gray-50 dark:bg-gray-900/40 px-6 py-3 border-b border-gray-200 dark:border-gray-700 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 items-center">
                <div class="col-span-3">Nama Unit</div>
                <div class="col-span-1">Level</div>
                <div class="col-span-1">Lv 1</div>
                <div class="col-span-1">Lv 2</div>
                <div class="col-span-1">Lv 3</div>
                <div class="col-span-1">LV 4</div>
                <div class="col-span-3">Koordinat</div>
                <div class="col-span-2 text-center">Action</div>
            </div>

            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($units as $unit)
                    @php
                        $lvl1Name = '—';
                        $lvl2Name = '—';
                        $lvl3Name = '—';
                        $lvl4Name = '—';

                        if ($unit->level == 2) {
                            // UPT
                            $lvl1Name = optional($unit->parent)->name ?? '—';

                        } elseif ($unit->level == 3) {
                            // ULTG / ULP
                            $lvl2Name = optional($unit->parent)->name ?? '—';
                            $lvl1Name = optional(optional($unit->parent)->parent)->name ?? '—';

                        } elseif ($unit->level == 4) {
                            // GI
                            $lvl4Name = $unit->name;
                            $lvl3Name = optional($unit->parent)->name ?? '—';
                            $lvl2Name = optional(optional($unit->parent)->parent)->name ?? '—';
                            $lvl1Name = optional(optional(optional($unit->parent)->parent)->parent)->name ?? '—';
                        }
                    @endphp
                        <div class="grid grid-cols-12 items-center px-6 py-3.5 text-sm hover:bg-gray-50/50 dark:hover:bg-gray-700/40 transition-colors">
                        
                        {{-- Nama Unit --}}
                        <div class="col-span-3 font-semibold text-[#004A54] dark:text-accent-400">
                            {{ $unit->name }}
                        </div>

                        {{-- Level Badge --}}
                        <div class="col-span-1">
                            <span class="px-2 py-1 text-xs font-semibold rounded-md
                                {{ $unit->level == 1 ? 'bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-300' : '' }}
                                {{ $unit->level == 2 ? 'bg-sky-100 dark:bg-sky-900/40 text-sky-800 dark:text-sky-300' : '' }}
                                {{ $unit->level == 3 ? 'bg-purple-100 dark:bg-purple-900/40 text-purple-800 dark:text-purple-300' : '' }}
                                {{ $unit->level == 4 ? 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300' : '' }}">
                                Level {{ $unit->level }}
                            </span>
                        </div>

                        {{-- Kolom Lv 1 --}}
                        <div class="col-span-1 text-xs text-gray-600 dark:text-gray-300 truncate pr-2">
                            {{ $lvl1Name }}
                        </div>

                        {{-- Kolom Lv 2 --}}
                        <div class="col-span-1 text-xs text-gray-600 dark:text-gray-300 truncate pr-2">
                            {{ $lvl2Name }}
                        </div>

                        {{-- Kolom Lv 3 --}}
                        <div class="col-span-1 text-xs text-gray-600 dark:text-gray-300 truncate pr-2">
                            {{ $lvl3Name }}
                        </div>

                        {{-- Kolom Lv 4 --}}
                        <div class="col-span-1 text-xs text-gray-600 dark:text-gray-300 truncate pr-2">
                            {{ $lvl4Name }}
                        </div>

                        {{-- Koordinat --}}
                        <div class="col-span-2 text-xs text-gray-500 dark:text-gray-400">
                            @if ($unit->latitude && $unit->longitude)
                                {{ $unit->latitude }}, {{ $unit->longitude }}
                            @else
                                <span class="italic text-gray-300 dark:text-gray-600">belum ada</span>
                            @endif
                        </div>

                        {{-- Action Buttons --}}
                        <div class="col-span-2 flex items-center justify-center gap-1.5">
                            @if ($unit->latitude && $unit->longitude)
                                <button type="button" class="distance-btn text-gray-400 dark:text-gray-500 hover:text-[#004A54] dark:hover:text-accent-400 p-1.5 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                                    data-url="{{ route('manage-unit.distance', $unit) }}" title="Hitung Jarak">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                    </svg>
                                </button>
                            @endif
                            <a href="{{ route('manage-unit.edit', $unit) }}" class="text-gray-400 dark:text-gray-500 hover:text-[#004A54] dark:hover:text-accent-400 p-1.5 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </a>
                            <form action="{{ route('manage-unit.destroy', $unit) }}" method="POST" onsubmit="return confirm('Hapus unit {{ $unit->name }}?')" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 dark:text-gray-500 hover:text-red-600 dark:hover:text-red-400 p-1.5 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150 focus:outline-none" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-gray-400 dark:text-gray-500 bg-white dark:bg-gray-800">
                        <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Belum ada data unit yang cocok ditemukan.
                    </div>
                @endforelse
            </div>

            {{-- Footer Pagination --}}
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                    Menampilkan {{ $units->firstItem() ?? 0 }} - {{ $units->lastItem() ?? 0 }} dari {{ $units->total() }} unit
                </div>
                <div class="laravel-pagination">
                    {{ $units->onEachSide(0)->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal hasil hitung jarak --}}
<div id="distanceModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div id="distanceModalBackdrop" class="absolute inset-0 bg-black/50"></div>
    <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-sm p-6">
        <h3 class="text-base font-bold text-pln-800 dark:text-white">Hasil Perhitungan Jarak</h3>
        <div id="distanceModalBody" class="mt-4 text-sm text-gray-600 dark:text-gray-300">Menghitung...</div>
        <button type="button" id="distanceModalClose" class="mt-5 w-full rounded-md bg-pln-800 hover:bg-pln-700 text-white text-sm font-semibold py-2.5">Tutup</button>
    </div>
</div>

<script>
    // Sidebar admin submenu toggle
    const adminMenuToggle = document.getElementById('adminMenuToggle');
    const adminSubmenu = document.getElementById('adminSubmenu');
    const adminMenuChevron = document.getElementById('adminMenuChevron');

    if (adminMenuToggle && adminSubmenu) {
        adminMenuToggle.addEventListener('click', () => {
            adminSubmenu.classList.toggle('hidden');
            adminMenuChevron.classList.toggle('rotate-180');
        });
    }

    // Modal hitung jarak
    const distanceModal = document.getElementById('distanceModal');
    const distanceModalBody = document.getElementById('distanceModalBody');

    document.querySelectorAll('.distance-btn').forEach((btn) => {
        btn.addEventListener('click', async () => {
            distanceModal.classList.remove('hidden');
            distanceModalBody.textContent = 'Menghitung...';

            try {
                const res = await fetch(btn.dataset.url, { headers: { 'Accept': 'application/json' } });
                const data = await res.json();

                if (data.error) {
                    distanceModalBody.innerHTML = `<p class="text-red-600 dark:text-red-400">${data.error}</p>`;
                } else {
                    distanceModalBody.innerHTML = `
                        <p><span class="font-semibold">${data.from}</span> → <span class="font-semibold">${data.to}</span></p>
                        <p class="mt-2 text-2xl font-bold text-pln-800 dark:text-white">${data.distance_km} km</p>
                        <p class="mt-1 text-xs text-gray-400">Jarak garis lurus (Haversine), belum mengikuti rute tower.</p>
                    `;
                }
            } catch (e) {
                distanceModalBody.innerHTML = '<p class="text-red-600">Gagal mengambil data.</p>';
            }
        });
    });

    const distanceModalClose = document.getElementById('distanceModalClose');
    const distanceModalBackdrop = document.getElementById('distanceModalBackdrop');
    if (distanceModalClose) distanceModalClose.addEventListener('click', () => distanceModal.classList.add('hidden'));
    if (distanceModalBackdrop) distanceModalBackdrop.addEventListener('click', () => distanceModal.classList.add('hidden'));
</script>
@endsection