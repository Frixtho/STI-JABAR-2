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

            {{-- Admin dropdown --}}
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
                    <a href="#" class="block px-2 py-2 text-sm text-pln-100 hover:text-white">Manage User</a>
                </div>
            </div>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium text-pln-100 hover:bg-white/5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 0 1 0 .255c-.007.378.138.751.43.991l1.005.828c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 0 1 0-.255c.007-.378-.138-.751-.43-.991l-1.004-.828a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.281Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
                Settings
            </a>
        </nav>

        <div class="p-4 border-t border-white/10">
            <div class="flex items-center gap-3 px-2 py-2 rounded-md bg-white/5">
                <div class="w-8 h-8 rounded-full bg-accent-400 text-pln-800 font-bold text-xs flex items-center justify-center">
                    AC
                </div>
                <div class="leading-tight">
                    <p class="text-sm font-semibold text-white">Admin Central</p>
                    <p class="text-[11px] text-pln-100/70">admin@pln.co.id</p>
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
        <header class="bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between gap-4">
            <div class="relative w-full max-w-sm">
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <circle cx="11" cy="11" r="7" stroke-linecap="round" stroke-linejoin="round" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="m20 20-3.5-3.5" />
                    </svg>
                </span>
                <input type="text" placeholder="Cari laporan atau transaksi..."
                    class="w-full rounded-md border border-gray-200 bg-gray-50 py-2 pl-10 pr-3 text-sm text-gray-700 placeholder-gray-400 focus:border-pln-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-pln-700/20">
            </div>

            <div class="flex items-center gap-5 shrink-0">
                <button type="button" class="relative text-gray-500 hover:text-gray-700">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.85 23.85 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
                    <span class="absolute -top-0.5 -right-0.5 w-2 h-2 rounded-full bg-red-500"></span>
                </button>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold text-pln-800">Profile</span>
                    <div class="w-8 h-8 rounded-full bg-pln-800 text-white flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0" />
                        </svg>
                    </div>
                </div>
            </div>
        </header>

        <main class="p-6 space-y-6">

            {{-- Greeting --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-bold text-pln-800">
                        Selamat Pagi, {{ explode(' ', auth()->user()->name)[0] ?? 'Admin' }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Berikut ringkasan data finansial utilitas hari ini.
                    </p>
                </div>
                <span class="inline-flex items-center gap-2 rounded-md border border-gray-200 bg-white px-3 py-1.5 text-sm text-gray-600 self-start sm:self-auto">
                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0V11.25A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                    {{ now()->translatedFormat('l, d F Y') }}
                </span>
            </div>

            {{-- Stat cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

                <div class="bg-white rounded-xl border border-gray-100 p-5">
                    <div class="flex items-start justify-between">
                        <div class="w-9 h-9 rounded-lg bg-pln-800 text-white flex items-center justify-center">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 8.25v8.25a2.25 2.25 0 0 0 2.25 2.25h15a2.25 2.25 0 0 0 2.25-2.25V8.25M2.25 8.25l1.5-3.75A2.25 2.25 0 0 1 5.87 3h12.26a2.25 2.25 0 0 1 2.12 1.5l1.5 3.75M12 13.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />
                            </svg>
                        </div>
                        <span class="inline-flex items-center gap-1 text-xs font-medium text-green-600 bg-green-50 rounded-full px-2 py-0.5">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5 19.5 4.5m0 0H8.25m11.25 0v11.25" />
                            </svg>
                            5.2%
                        </span>
                    </div>
                    <p class="mt-4 text-[11px] font-semibold tracking-wide text-gray-400 uppercase">Total Pendapatan</p>
                    <p class="mt-1 text-xl font-bold text-pln-800">IDR 12.5T</p>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 p-5">
                    <div class="flex items-start justify-between">
                        <div class="w-9 h-9 rounded-lg bg-accent-400 text-pln-800 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0" />
                            </svg>
                        </div>
                        <span class="inline-flex items-center gap-1 text-xs font-medium text-green-600 bg-green-50 rounded-full px-2 py-0.5">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5 19.5 4.5m0 0H8.25m11.25 0v11.25" />
                            </svg>
                            1.2%
                        </span>
                    </div>
                    <p class="mt-4 text-[11px] font-semibold tracking-wide text-gray-400 uppercase">Pelanggan Aktif</p>
                    <p class="mt-1 text-xl font-bold text-pln-800">2.4M</p>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 p-5">
                    <div class="flex items-start justify-between">
                        <div class="w-9 h-9 rounded-lg bg-sky-500 text-white flex items-center justify-center">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5 13.5 3l-1.5 6.75h8.25L10.5 21l1.5-7.5H3.75Z" />
                            </svg>
                        </div>
                        <span class="inline-flex items-center gap-1 text-xs font-medium text-red-500 bg-red-50 rounded-full px-2 py-0.5">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 4.5 4.5 19.5m0 0h11.25M4.5 19.5V8.25" />
                            </svg>
                            0.5%
                        </span>
                    </div>
                    <p class="mt-4 text-[11px] font-semibold tracking-wide text-gray-400 uppercase">Konsumsi Daya</p>
                    <p class="mt-1 text-xl font-bold text-pln-800">450 KWh</p>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 p-5">
                    <div class="flex items-start justify-between">
                        <div class="w-9 h-9 rounded-lg bg-red-50 text-red-500 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008ZM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                        <span class="inline-flex items-center text-xs font-semibold text-red-600 bg-red-50 rounded-full px-2 py-0.5">
                            PERLU TINDAKAN
                        </span>
                    </div>
                    <p class="mt-4 text-[11px] font-semibold tracking-wide text-gray-400 uppercase">Tagihan Tertunda</p>
                    <p class="mt-1 text-xl font-bold text-red-600">IDR 120M</p>
                </div>
            </div>

            {{-- Chart + distribution --}}
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">

                {{-- Bar chart --}}
                <div class="xl:col-span-2 bg-white rounded-xl border border-gray-100 p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 class="text-sm font-bold text-pln-800">Tren Pendapatan Bulanan</h2>
                            <p class="text-xs text-gray-400 mt-0.5">Laporan konsolidasi Q1 - Q3 2023</p>
                        </div>
                        <select class="text-xs border border-gray-200 rounded-md px-2 py-1.5 text-gray-600 focus:outline-none">
                            <option>Tahun 2023</option>
                            <option>Tahun 2024</option>
                            <option>Tahun 2025</option>
                        </select>
                    </div>

                    @php
                        $chart = [
                            ['label' => 'Januari', 'value' => 45, 'color' => 'bg-sky-100'],
                            ['label' => 'Februari', 'value' => 65, 'color' => 'bg-sky-200'],
                            ['label' => 'Maret', 'value' => 55, 'color' => 'bg-sky-200'],
                            ['label' => 'April', 'value' => 80, 'color' => 'bg-sky-300'],
                            ['label' => 'Mei', 'value' => 92, 'color' => 'bg-accent-400'],
                            ['label' => 'Juni', 'value' => 97, 'color' => 'bg-accent-400'],
                        ];
                    @endphp

                    <div class="mt-8 flex items-end justify-between gap-4 h-48">
                        @foreach ($chart as $bar)
                            <div class="flex-1 flex flex-col items-center gap-2">
                                <div class="w-full max-w-10 {{ $bar['color'] }} rounded-t-md" style="height: {{ $bar['value'] }}%"></div>
                                <span class="text-[11px] text-gray-400">{{ $bar['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Distribution --}}
                <div class="bg-white rounded-xl border border-gray-100 p-5">
                    <h2 class="text-sm font-bold text-pln-800">Distribusi Pelanggan</h2>

                    <div class="mt-6 flex items-center justify-center">
                        <div class="w-40 h-40 rounded-2xl border-[6px] border-pln-800 flex flex-col items-center justify-center">
                            <p class="text-xl font-bold text-pln-800">2.4M</p>
                            <p class="text-[10px] tracking-wide text-gray-400 uppercase">Total Unit</p>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="flex items-center gap-2 text-gray-600">
                                <span class="w-2.5 h-2.5 rounded-full bg-pln-800"></span>
                                Rumah Tangga
                            </span>
                            <span class="font-semibold text-gray-700">65%</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="flex items-center gap-2 text-gray-600">
                                <span class="w-2.5 h-2.5 rounded-full bg-accent-400"></span>
                                Industri
                            </span>
                            <span class="font-semibold text-gray-700">25%</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="flex items-center gap-2 text-gray-600">
                                <span class="w-2.5 h-2.5 rounded-full bg-sky-400"></span>
                                Komersial
                            </span>
                            <span class="font-semibold text-gray-700">10%</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent transactions --}}
            <div class="bg-white rounded-xl border border-gray-100">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-bold text-pln-800">Transaksi Terbaru</h2>
                    <a href="#" class="text-xs font-semibold text-pln-700 hover:text-pln-800">Lihat Semua →</a>
                </div>

                @php
                    $transactions = [
                        ['id' => 'PLN-99201', 'name' => 'Bambang Widjaya', 'type' => 'Residential', 'status' => 'BERHASIL', 'amount' => 'IDR 350,000'],
                        ['id' => 'PLN-99202', 'name' => 'PT. Maju Bersama', 'type' => 'Industrial', 'status' => 'PENDING', 'amount' => 'IDR 45,200,000'],
                    ];
                @endphp

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-[11px] font-semibold tracking-wide text-gray-400 uppercase">
                                <th class="px-5 py-3">ID Transaksi</th>
                                <th class="px-5 py-3">Nama Pelanggan</th>
                                <th class="px-5 py-3">Tipe</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3 text-right">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($transactions as $trx)
                                <tr>
                                    <td class="px-5 py-3.5 font-semibold text-pln-800">{{ $trx['id'] }}</td>
                                    <td class="px-5 py-3.5 text-gray-700">{{ $trx['name'] }}</td>
                                    <td class="px-5 py-3.5 text-gray-500">{{ $trx['type'] }}</td>
                                    <td class="px-5 py-3.5">
                                        @if ($trx['status'] === 'BERHASIL')
                                            <span class="inline-flex text-[11px] font-semibold text-green-700 bg-green-50 rounded-full px-2.5 py-1">BERHASIL</span>
                                        @else
                                            <span class="inline-flex text-[11px] font-semibold text-amber-700 bg-amber-50 rounded-full px-2.5 py-1">PENDING</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 text-right text-gray-700">{{ $trx['amount'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    const adminMenuToggle = document.getElementById('adminMenuToggle');
    const adminSubmenu = document.getElementById('adminSubmenu');
    const adminMenuChevron = document.getElementById('adminMenuChevron');

    if (adminMenuToggle && adminSubmenu) {
        adminMenuToggle.addEventListener('click', () => {
            adminSubmenu.classList.toggle('hidden');
            adminMenuChevron.classList.toggle('rotate-180');
        });
    }
</script>
@endsection