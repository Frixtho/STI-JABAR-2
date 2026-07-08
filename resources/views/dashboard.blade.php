@extends('layouts.app', ['title' => 'Dashboard — PLN Financial'])

@section('content')
<div class="min-h-screen flex">

    {{-- ===================== SIDEBAR ===================== --}}
    <aside class="hidden lg:flex lg:flex-col lg:w-60 bg-pln-800 text-white shrink-0">
        <div class="px-6 py-6">
            <p class="font-bold text-lg leading-tight">PLN Financial</p>
            <p class="text-[10px] tracking-[0.2em] text-accent-400">UTILITY MANAGEMENT</p>
        </div>

        <nav class="flex-1 px-4 space-y-1 mt-2">
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium bg-white/10 text-white">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                </svg>
                Dashboard
            </a>

            <div>
                <button type="button" id="adminMenuToggle"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-md text-sm font-medium text-pln-100 hover:bg-white/5">
                    <span class="flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0" />
                        </svg>
                        Admin
                    </span>
                    <svg id="adminMenuChevron" class="w-3.5 h-3.5 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>
                <div id="adminSubmenu" class="hidden ml-7 mt-1 border-l border-white/10 pl-3">
                    <a href="{{ route('manage-user') }}" class="block px-2 py-2 text-sm text-pln-100 hover:text-white">Manage User</a>
                </div>
            </div>

            <a href="{{ route('settings') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium text-pln-100 hover:bg-white/5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 0 1 0 .255c-.007.378.138.751.43.991l1.005.828c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 0 1 0-.255c.007-.378-.138-.751-.43-.991l-1.004-.828a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.281Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
                Settings
            </a>
        </nav>

        <div class="p-4 border-t border-white/10">
            <div class="flex items-center gap-3 px-2 py-2 rounded-md bg-white/5">
                <div class="w-8 h-8 rounded-full bg-accent-400 text-pln-800 font-bold text-xs flex items-center justify-center uppercase shrink-0">
                    {{ implode('', array_map(fn($w) => $w[0] ?? '', array_slice(explode(' ', auth()->user()->name), 0, 2))) }}
                </div>
                <div class="leading-tight min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[11px] text-pln-100/70 truncate">{{ auth()->user()->email }}</p>
                    <span class="inline-flex items-center mt-1 text-[9px] font-bold uppercase tracking-wide px-1.5 py-0.5 rounded
                        {{ strcasecmp(auth()->user()->role, 'Admin') === 0 ? 'bg-accent-400 text-pln-800' : 'bg-white/10 text-pln-100' }}">
                        {{ auth()->user()->role }}
                    </span>
                </div>
            </div>
            <div class="mt-3 space-y-1">
                <a href="#" class="flex items-center gap-2 px-2 py-1.5 text-sm text-pln-100 hover:text-white">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="9" stroke-linecap="round" stroke-linejoin="round" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.5 9a2.5 2.5 0 1 1 3.5 2.29c-.7.32-1 .8-1 1.71M12 17h.01" />
                    </svg>
                    Help
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-2 py-1.5 text-sm text-pln-100 hover:text-white">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>

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
                <input type="text" placeholder="Cari anggaran, proyek, atau pengajuan..."
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

        <main class="p-6 space-y-6">

            {{-- Greeting --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-bold text-pln-800 dark:text-white">
                        Selamat Pagi, {{ explode(' ', auth()->user()->name)[0] ?? 'Admin' }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Ringkasan anggaran dan aktivitas keuangan unit hari ini.
                    </p>
                </div>
                <span class="inline-flex items-center gap-2 rounded-md border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-1.5 text-sm text-gray-600 dark:text-gray-300 self-start sm:self-auto">
                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0V11.25A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                    {{ now()->translatedFormat('l, d F Y') }}
                </span>
            </div>

            {{-- Stat cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5">
                    <div class="flex items-start justify-between">
                        <div class="w-9 h-9 rounded-lg bg-pln-800 text-white flex items-center justify-center">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 8.25v8.25a2.25 2.25 0 0 0 2.25 2.25h15a2.25 2.25 0 0 0 2.25-2.25V8.25M2.25 8.25l1.5-3.75A2.25 2.25 0 0 1 5.87 3h12.26a2.25 2.25 0 0 1 2.12 1.5l1.5 3.75M12 13.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />
                            </svg>
                        </div>
                    </div>
                    <p class="mt-4 text-[11px] font-semibold tracking-wide text-gray-400 dark:text-gray-500 uppercase">Total Anggaran Tahun Ini</p>
                    <p class="mt-1 text-xl font-bold text-pln-800 dark:text-white">Rp 84.2M</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5">
                    <div class="flex items-start justify-between">
                        <div class="w-9 h-9 rounded-lg bg-accent-400 text-pln-800 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                    </div>
                    <p class="mt-4 text-[11px] font-semibold tracking-wide text-gray-400 dark:text-gray-500 uppercase">Realisasi Anggaran</p>
                    <p class="mt-1 text-xl font-bold text-pln-800 dark:text-white">61.4%</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5">
                    <div class="flex items-start justify-between">
                        <div class="w-9 h-9 rounded-lg bg-sky-500 text-white flex items-center justify-center">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21" />
                            </svg>
                        </div>
                    </div>
                    <p class="mt-4 text-[11px] font-semibold tracking-wide text-gray-400 dark:text-gray-500 uppercase">Proyek Berjalan</p>
                    <p class="mt-1 text-xl font-bold text-pln-800 dark:text-white">7 Proyek</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5">
                    <div class="flex items-start justify-between">
                        <div class="w-9 h-9 rounded-lg bg-red-50 dark:bg-red-900/30 text-red-500 dark:text-red-400 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008ZM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                        <span class="inline-flex items-center text-xs font-semibold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/30 rounded-full px-2 py-0.5">
                            PERLU APPROVAL
                        </span>
                    </div>
                    <p class="mt-4 text-[11px] font-semibold tracking-wide text-gray-400 dark:text-gray-500 uppercase">Pengajuan Tertunda</p>
                    <p class="mt-1 text-xl font-bold text-red-600 dark:text-red-400">4 Pengajuan</p>
                </div>
            </div>

            {{-- Chart + distribution --}}
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">

                <div class="xl:col-span-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 class="text-sm font-bold text-pln-800 dark:text-white">Realisasi Anggaran Bulanan</h2>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Tahun berjalan — klik salah satu batang untuk lihat detail</p>
                        </div>
                        <select class="text-xs border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md px-2 py-1.5 text-gray-600 dark:text-gray-200 focus:outline-none">
                            <option>Tahun 2026</option>
                            <option>Tahun 2025</option>
                        </select>
                    </div>

                    @php
                        $chart = [
                            ['label' => 'Jan', 'value' => 40, 'color' => 'bg-sky-100 dark:bg-sky-900/50', 'anggaran' => 'Rp 7.0M', 'realisasi' => 'Rp 4.1M', 'persen' => '58.6%'],
                            ['label' => 'Feb', 'value' => 55, 'color' => 'bg-sky-200 dark:bg-sky-800/60', 'anggaran' => 'Rp 7.0M', 'realisasi' => 'Rp 4.9M', 'persen' => '70.0%'],
                            ['label' => 'Mar', 'value' => 48, 'color' => 'bg-sky-200 dark:bg-sky-800/60', 'anggaran' => 'Rp 7.0M', 'realisasi' => 'Rp 4.3M', 'persen' => '61.4%'],
                            ['label' => 'Apr', 'value' => 62, 'color' => 'bg-sky-300 dark:bg-sky-700/70', 'anggaran' => 'Rp 7.0M', 'realisasi' => 'Rp 5.2M', 'persen' => '74.3%'],
                            ['label' => 'Mei', 'value' => 70, 'color' => 'bg-accent-400', 'anggaran' => 'Rp 7.0M', 'realisasi' => 'Rp 5.6M', 'persen' => '80.0%'],
                            ['label' => 'Jun', 'value' => 61, 'color' => 'bg-accent-400', 'anggaran' => 'Rp 7.0M', 'realisasi' => 'Rp 4.8M', 'persen' => '68.6%'],
                        ];
                    @endphp

                    <div class="mt-8 flex items-end justify-between gap-4 h-48">
                        @foreach ($chart as $bar)
                            <button type="button"
                                class="chart-bar-btn flex-1 flex flex-col items-center gap-2 group cursor-pointer"
                                data-label="{{ $bar['label'] }}"
                                data-anggaran="{{ $bar['anggaran'] }}"
                                data-realisasi="{{ $bar['realisasi'] }}"
                                data-persen="{{ $bar['persen'] }}">
                                <div class="w-full max-w-10 {{ $bar['color'] }} rounded-t-md transition-transform group-hover:scale-x-110 group-hover:brightness-95" style="height: {{ $bar['value'] }}%"></div>
                                <span class="text-[11px] text-gray-400 dark:text-gray-500 group-hover:text-pln-700 dark:group-hover:text-accent-400 group-hover:font-semibold transition-colors">{{ $bar['label'] }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5">
                    <h2 class="text-sm font-bold text-pln-800 dark:text-white">Alokasi Anggaran</h2>

                    <div class="mt-6 flex items-center justify-center">
                        <div class="w-40 h-40 rounded-2xl border-[6px] border-pln-800 dark:border-accent-400 flex flex-col items-center justify-center">
                            <p class="text-xl font-bold text-pln-800 dark:text-white">Rp 84.2M</p>
                            <p class="text-[10px] tracking-wide text-gray-400 dark:text-gray-500 uppercase">Total Anggaran</p>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                                <span class="w-2.5 h-2.5 rounded-full bg-pln-800 dark:bg-accent-400"></span>
                                Investasi (CAPEX)
                            </span>
                            <span class="font-semibold text-gray-700 dark:text-gray-200">55%</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                                <span class="w-2.5 h-2.5 rounded-full bg-accent-400"></span>
                                Pemeliharaan
                            </span>
                            <span class="font-semibold text-gray-700 dark:text-gray-200">30%</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                                <span class="w-2.5 h-2.5 rounded-full bg-sky-400"></span>
                                Operasional
                            </span>
                            <span class="font-semibold text-gray-700 dark:text-gray-200">15%</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent submissions --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-sm font-bold text-pln-800 dark:text-white">Pengajuan Terbaru</h2>
                    <a href="#" class="text-xs font-semibold text-pln-700 dark:text-accent-400 hover:text-pln-800 dark:hover:text-accent-500">Lihat Semua →</a>
                </div>

                @php
                    $submissions = [
                        [
                            'id' => 'PGJ-0091', 'name' => 'Pemeliharaan Gardu Induk Ungaran', 'type' => 'Pemeliharaan',
                            'status' => 'DISETUJUI', 'amount' => 'Rp 320,000,000', 'date' => '30 Juni 2026',
                            'unit' => 'Bidang Har', 'pemohon' => 'Ir. Sutrisno',
                        ],
                        [
                            'id' => 'PGJ-0092', 'name' => 'Pengadaan Material SUTT Batang-Weleri', 'type' => 'Investasi',
                            'status' => 'MENUNGGU', 'amount' => 'Rp 1,250,000,000', 'date' => '01 Juli 2026',
                            'unit' => 'Bidang Konstruksi', 'pemohon' => 'Dian Kusuma',
                        ],
                    ];
                @endphp

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-[11px] font-semibold tracking-wide text-gray-400 dark:text-gray-500 uppercase">
                                <th class="px-5 py-3">No. Pengajuan</th>
                                <th class="px-5 py-3">Uraian</th>
                                <th class="px-5 py-3">Kategori</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3 text-right">Nilai</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                            @foreach ($submissions as $item)
                                <tr class="sub-row cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors"
                                    data-id="{{ $item['id'] }}"
                                    data-name="{{ $item['name'] }}"
                                    data-type="{{ $item['type'] }}"
                                    data-status="{{ $item['status'] }}"
                                    data-amount="{{ $item['amount'] }}"
                                    data-date="{{ $item['date'] }}"
                                    data-unit="{{ $item['unit'] }}"
                                    data-pemohon="{{ $item['pemohon'] }}">
                                    <td class="px-5 py-3.5 font-semibold text-pln-800 dark:text-white">{{ $item['id'] }}</td>
                                    <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $item['name'] }}</td>
                                    <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400">{{ $item['type'] }}</td>
                                    <td class="px-5 py-3.5">
                                        @if ($item['status'] === 'DISETUJUI')
                                            <span class="inline-flex text-[11px] font-semibold text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/30 rounded-full px-2.5 py-1">DISETUJUI</span>
                                        @else
                                            <span class="inline-flex text-[11px] font-semibold text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/30 rounded-full px-2.5 py-1">MENUNGGU</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 text-right text-gray-700 dark:text-gray-300">{{ $item['amount'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

{{-- ===================== MODAL: DETAIL BULAN ===================== --}}
<div id="chartModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div id="chartModalBackdrop" class="absolute inset-0 bg-black/50"></div>
    <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-sm p-6">
        <button type="button" id="chartModalClose" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="flex items-center gap-2">
            <div class="w-9 h-9 rounded-lg bg-pln-800 text-white flex items-center justify-center">
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h12M3.75 3h-1.5m1.5 0H21m0 0v1.5m0-1.5v11.25a2.25 2.25 0 0 1-2.25 2.25H15m6-13.5-6.75 6.75L11 12l-4.5 4.5" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide font-semibold">Detail Bulan</p>
                <h3 id="chartModalLabel" class="text-lg font-bold text-pln-800 dark:text-white">—</h3>
            </div>
        </div>

        <div class="mt-5 space-y-3">
            <div class="flex items-center justify-between rounded-lg bg-gray-50 dark:bg-gray-700/50 px-4 py-3">
                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Anggaran Bulan Ini</span>
                <span id="chartModalAnggaran" class="text-sm font-bold text-pln-800 dark:text-white">—</span>
            </div>
            <div class="flex items-center justify-between rounded-lg bg-gray-50 dark:bg-gray-700/50 px-4 py-3">
                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Realisasi</span>
                <span id="chartModalRealisasi" class="text-sm font-bold text-pln-800 dark:text-white">—</span>
            </div>
            <div class="flex items-center justify-between rounded-lg bg-gray-50 dark:bg-gray-700/50 px-4 py-3">
                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Persentase Serapan</span>
                <span id="chartModalPersen" class="text-sm font-bold text-pln-800 dark:text-white">—</span>
            </div>
        </div>

        <button type="button" id="chartModalCloseBtn" class="mt-6 w-full rounded-md bg-pln-800 hover:bg-pln-700 text-white text-sm font-semibold py-2.5">
            Tutup
        </button>
    </div>
</div>

{{-- ===================== MODAL: DETAIL PENGAJUAN ===================== --}}
<div id="subModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div id="subModalBackdrop" class="absolute inset-0 bg-black/50"></div>
    <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-md p-6">
        <button type="button" id="subModalClose" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="flex items-center justify-between pr-6">
            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide font-semibold">Detail Pengajuan</p>
                <h3 id="subModalId" class="text-lg font-bold text-pln-800 dark:text-white">—</h3>
            </div>
            <span id="subModalStatus" class="text-[11px] font-semibold rounded-full px-2.5 py-1">—</span>
        </div>

        <div class="mt-5 divide-y divide-gray-100 dark:divide-gray-700">
            <div class="flex items-center justify-between py-2.5">
                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Uraian</span>
                <span id="subModalName" class="text-sm font-medium text-gray-800 dark:text-gray-100 text-right max-w-[60%]">—</span>
            </div>
            <div class="flex items-center justify-between py-2.5">
                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Kategori</span>
                <span id="subModalType" class="text-sm font-medium text-gray-800 dark:text-gray-100">—</span>
            </div>
            <div class="flex items-center justify-between py-2.5">
                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Bidang / Unit</span>
                <span id="subModalUnit" class="text-sm font-medium text-gray-800 dark:text-gray-100">—</span>
            </div>
            <div class="flex items-center justify-between py-2.5">
                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Pemohon</span>
                <span id="subModalPemohon" class="text-sm font-medium text-gray-800 dark:text-gray-100">—</span>
            </div>
            <div class="flex items-center justify-between py-2.5">
                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Tanggal</span>
                <span id="subModalDate" class="text-sm font-medium text-gray-800 dark:text-gray-100">—</span>
            </div>
            <div class="flex items-center justify-between py-3">
                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Nilai</span>
                <span id="subModalAmount" class="text-base font-bold text-pln-800 dark:text-white">—</span>
            </div>
        </div>

        <button type="button" id="subModalCloseBtn" class="mt-4 w-full rounded-md bg-pln-800 hover:bg-pln-700 text-white text-sm font-semibold py-2.5">
            Tutup
        </button>
    </div>
</div>

<script>
    // Admin dropdown toggle
    const adminMenuToggle = document.getElementById('adminMenuToggle');
    const adminSubmenu = document.getElementById('adminSubmenu');
    const adminMenuChevron = document.getElementById('adminMenuChevron');
    if (adminMenuToggle && adminSubmenu) {
        adminMenuToggle.addEventListener('click', () => {
            adminSubmenu.classList.toggle('hidden');
            adminMenuChevron.classList.toggle('rotate-180');
        });
    }

    // ===== CHART BAR MODAL =====
    const chartModal = document.getElementById('chartModal');
    const chartModalLabel = document.getElementById('chartModalLabel');
    const chartModalAnggaran = document.getElementById('chartModalAnggaran');
    const chartModalRealisasi = document.getElementById('chartModalRealisasi');
    const chartModalPersen = document.getElementById('chartModalPersen');

    function openChartModal(data) {
        chartModalLabel.textContent = data.label;
        chartModalAnggaran.textContent = data.anggaran;
        chartModalRealisasi.textContent = data.realisasi;
        chartModalPersen.textContent = data.persen;
        chartModal.classList.remove('hidden');
    }

    function closeChartModal() {
        chartModal.classList.add('hidden');
    }

    document.querySelectorAll('.chart-bar-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            openChartModal({
                label: btn.dataset.label,
                anggaran: btn.dataset.anggaran,
                realisasi: btn.dataset.realisasi,
                persen: btn.dataset.persen,
            });
        });
    });

    document.getElementById('chartModalBackdrop').addEventListener('click', closeChartModal);
    document.getElementById('chartModalClose').addEventListener('click', closeChartModal);
    document.getElementById('chartModalCloseBtn').addEventListener('click', closeChartModal);

    // ===== SUBMISSION ROW MODAL =====
    const subModal = document.getElementById('subModal');
    const subModalId = document.getElementById('subModalId');
    const subModalStatus = document.getElementById('subModalStatus');
    const subModalName = document.getElementById('subModalName');
    const subModalType = document.getElementById('subModalType');
    const subModalUnit = document.getElementById('subModalUnit');
    const subModalPemohon = document.getElementById('subModalPemohon');
    const subModalDate = document.getElementById('subModalDate');
    const subModalAmount = document.getElementById('subModalAmount');

    function openSubModal(data) {
        subModalId.textContent = data.id;
        subModalName.textContent = data.name;
        subModalType.textContent = data.type;
        subModalUnit.textContent = data.unit;
        subModalPemohon.textContent = data.pemohon;
        subModalDate.textContent = data.date;
        subModalAmount.textContent = data.amount;

        if (data.status === 'DISETUJUI') {
            subModalStatus.textContent = 'DISETUJUI';
            subModalStatus.className = 'text-[11px] font-semibold rounded-full px-2.5 py-1 text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/30';
        } else {
            subModalStatus.textContent = 'MENUNGGU';
            subModalStatus.className = 'text-[11px] font-semibold rounded-full px-2.5 py-1 text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/30';
        }

        subModal.classList.remove('hidden');
    }

    function closeSubModal() {
        subModal.classList.add('hidden');
    }

    document.querySelectorAll('.sub-row').forEach((row) => {
        row.addEventListener('click', () => {
            openSubModal({
                id: row.dataset.id,
                name: row.dataset.name,
                type: row.dataset.type,
                status: row.dataset.status,
                amount: row.dataset.amount,
                date: row.dataset.date,
                unit: row.dataset.unit,
                pemohon: row.dataset.pemohon,
            });
        });
    });

    document.getElementById('subModalBackdrop').addEventListener('click', closeSubModal);
    document.getElementById('subModalClose').addEventListener('click', closeSubModal);
    document.getElementById('subModalCloseBtn').addEventListener('click', closeSubModal);

    // Close modals on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeChartModal();
            closeSubModal();
        }
    });
</script>
@endsection