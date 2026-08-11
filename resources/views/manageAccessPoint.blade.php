@extends('layouts.app', ['title' => 'Manage ' . ($currentCategory->name ?? 'Asset') . ' — PLN Financial'])

@section('content')
<div class="min-h-screen flex bg-gray-50 dark:bg-gray-900">
    <div class="flex-1 min-w-0">

        {{-- Top bar --}}
        
        {{-- Top Bar (Search global & Profile User) --}}
        <header class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 px-6 py-4 flex items-center justify-between gap-4">
            <div class="relative w-full max-w-sm">
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <circle cx="11" cy="11" r="7" stroke-linecap="round" stroke-linejoin="round" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="m20 20-3.5-3.5" />
                    </svg>
                </span>
                <input type="text" placeholder="Cari data..."
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
                        <p class="text-sm font-semibold text-pln-800 dark:text-white">{{ auth()->user()->name ?? 'Admin PLN' }}</p>
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-accent-500">
                            {{ auth()->user()->role ?? 'ADMIN' }}
                        </p>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-pln-800 text-white flex items-center justify-center font-bold text-xs uppercase shrink-0">
                        {{ implode('', array_map(fn($w) => $w[0] ?? '', array_slice(explode(' ', auth()->user()->name ?? 'Admin PLN'), 0, 2))) }}
                    </div>
                </div>
            </div>
        </header>

        {{-- Main Content Section --}}
        <div class="px-6 space-y-4">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-1.5 text-sm">
                <a href="{{ route('dashboard') }}" class="text-gray-400 dark:text-gray-500 hover:text-[#004A54] dark:hover:text-accent-400 transition-colors">Dashboard</a>
                <svg class="w-3.5 h-3.5 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
                <span class="font-semibold text-gray-700 dark:text-gray-200">Manage Asset: {{ $currentCategory->name ?? '' }}</span>
            </nav>

            {{-- Header Title & Action Buttons --}}
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div>
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wider font-bold">MANAGE ASSET</p>
                    <h1 class="text-xl font-bold text-pln-800 dark:text-white tracking-wide">{{ $currentCategory->name ?? 'Daftar Aset' }}</h1>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('manage-access-point.import.form') }}" class="inline-flex items-center gap-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                        </svg>
                        Import Data
                    </a>
                    <a href="{{ route('manage-access-point.create') }}" class="inline-flex items-center gap-2 bg-[#004A54] text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-[#00363d] transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah Access Point
                    </a>
                </div>
            </div>
        </div>

        {{-- =========================
            FLASH MESSAGE
        ========================== --}}
        @if(session('success'))
            <div class="rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        {{-- =========================
            FILTER
        ========================== --}}
        <div class="w-full bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
            <form
                method="GET"
                action="{{ route('manage-access-point') }}"
                class="w-full flex flex-row flex-wrap items-center gap-4"
            >
                {{-- SEARCH --}}
                <div class="flex-1 min-w-[250px]">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari ID, merk, model, SN, IP, MAC, lokasi..."
                        class="w-full rounded-lg border border-gray-300 bg-white py-2.5 px-4 text-sm text-gray-700 placeholder-gray-400 focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                    >
                </div>

                {{-- KONDISI --}}
                <div class="shrink-0">
                    <select
                        name="kondisi"
                        onchange="this.form.submit()"
                        class="rounded-lg border border-gray-300 bg-white py-2.5 px-3 text-sm text-gray-700 focus:border-[#004A54] focus:outline-none"
                    >
                        <option value="">Semua kondisi</option>
                        <option value="baik" @selected(request('kondisi') == 'baik')>Baik</option>
                        <option value="rusak" @selected(request('kondisi') == 'rusak')>Rusak</option>
                        <option value="perlu_perbaikan" @selected(request('kondisi') == 'perlu_perbaikan')>Perlu Perbaikan</option>
                    </select>
                </div>

                {{-- STATUS --}}
                <div class="shrink-0">
                    <select
                        name="status_operasional"
                        onchange="this.form.submit()"
                        class="rounded-lg border border-gray-300 bg-white py-2.5 px-3 text-sm text-gray-700 focus:border-[#004A54] focus:outline-none"
                    >
                        <option value="">Semua status ops</option>
                        <option value="aktif" @selected(request('status_operasional') == 'aktif')>Aktif</option>
                        <option value="tidak_aktif" @selected(request('status_operasional') == 'tidak_aktif')>Tidak Aktif</option>
                    </select>
                </div>

                {{-- KRITIKALITAS --}}
                <div class="shrink-0">
                    <select
                        name="kritikalitas"
                        onchange="this.form.submit()"
                        class="rounded-lg border border-gray-300 bg-white py-2.5 px-3 text-sm text-gray-700 focus:border-[#004A54] focus:outline-none"
                    >
                        <option value="">Semua kritikalitas</option>
                        <option value="kritis" @selected(request('kritikalitas') == 'kritis')>Kritis</option>
                        <option value="penting" @selected(request('kritikalitas') == 'penting')>Penting</option>
                        <option value="normal" @selected(request('kritikalitas') == 'normal')>Normal</option>
                    </select>
                </div>

                {{-- LOKASI --}}
                <div class="shrink-0">
                    <select
                        name="lokasi"
                        onchange="this.form.submit()"
                        class="rounded-lg border border-gray-300 bg-white py-2.5 px-3 text-sm text-gray-700 focus:border-[#004A54] focus:outline-none"
                    >
                        <option value="">Semua lokasi</option>
                        @foreach(($lokasi ?? []) as $lok)
                            <option value="{{ $lok }}" @selected(request('lokasi') == $lok)>
                                {{ $lok }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- RESET --}}
                <div class="shrink-0">
                    <a
                        href="{{ route('manage-access-point') }}"
                        class="inline-block border-2 border-[#004A54] text-[#004A54] px-5 py-2 rounded-lg text-sm font-bold hover:bg-cyan-50"
                    >
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- =========================
            TABLE (SESUAI TEMPLATE EXCEL)
        ========================== --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    {{-- TABLE HEADER --}}
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold uppercase tracking-wider text-gray-500">
                            <th class="px-4 py-3">ID Aset</th>
                            <th class="px-4 py-3">Merk / Model</th>
                            <th class="px-4 py-3">Serial Number</th>
                            <th class="px-4 py-3">IP / MAC Address</th>
                            <th class="px-4 py-3">SSID & Frekuensi</th>
                            <th class="px-4 py-3">Lokasi Aset</th>
                            <th class="px-4 py-3">Kondisi</th>
                            <th class="px-4 py-3">Ops</th>
                            <th class="px-4 py-3">Kritikalitas</th>
                            <th class="px-4 py-3 text-center">Action</th>
                        </tr>
                    </thead>

                    {{-- TABLE BODY --}}
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse(($accessPoints ?? []) as $ap)
                            <tr class="hover:bg-gray-50/50">
                                {{-- ID ASET --}}
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <a href="{{ route('manage-access-point.edit', $ap->id) }}" class="font-semibold text-[#004A54] hover:underline">
                                        {{ $ap->id_aset ?? '-' }}
                                    </a>
                                </td>

                                {{-- MERK / MODEL --}}
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="font-semibold text-gray-700">{{ $ap->merk ?? '-' }}</div>
                                    <div class="text-xs text-gray-400">{{ $ap->model ?? '-' }}</div>
                                </td>

                                {{-- SERIAL NUMBER --}}
                                <td class="px-4 py-4 whitespace-nowrap text-gray-600">
                                    {{ $ap->serial_number ?? '-' }}
                                </td>

                                {{-- IP / MAC ADDRESS --}}
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-gray-600">{{ $ap->ip_address ?? '-' }}</div>
                                    <div class="text-xs text-gray-400">{{ $ap->mac_address ?? '-' }}</div>
                                </td>

                                {{-- SSID & FREKUENSI (Spesifik Access Point dari Template) --}}
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-gray-600 font-medium">{{ $ap->nama_ssid ?? '-' }}</div>
                                    <div class="text-xs text-gray-400">{{ $ap->frekuensi ?? '-' }} | PoE: {{ $ap->menggunakan_poe ?? '-' }}</div>
                                </td>

                                {{-- LOKASI --}}
                                <td class="px-4 py-4 whitespace-nowrap text-gray-600">
                                    <div>{{ $ap->lokasi_aset_saat_ini ?? '-' }}</div>
                                    <div class="text-xs text-gray-400">{{ $ap->keterangan_lokasi ?? '' }}</div>
                                </td>

                                {{-- KONDISI --}}
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @php
                                        $kondisi = $ap->status_kondisi ?? 'baik';
                                        $kondisiClass = match ($kondisi) {
                                            'baik' => 'bg-green-100 text-green-700',
                                            'rusak' => 'bg-red-100 text-red-700',
                                            'perlu_perbaikan' => 'bg-yellow-100 text-yellow-700',
                                            default => 'bg-gray-100 text-gray-600',
                                        };
                                    @endphp
                                    <span class="inline-flex px-2 py-1 rounded-md text-xs font-semibold {{ $kondisiClass }}">
                                        {{ str_replace('_', ' ', $kondisi) }}
                                    </span>
                                </td>

                                {{-- STATUS OPERASIONAL --}}
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @php
                                        $ops = $ap->status_operasional ?? 'tidak_aktif';
                                        $opsClass = $ops === 'aktif' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <span class="inline-flex px-2 py-1 rounded-md text-xs font-semibold {{ $opsClass }}">
                                        {{ str_replace('_', ' ', $ops) }}
                                    </span>
                                </td>

                                {{-- KRITIKALITAS --}}
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @php
                                        $krit = $ap->tingkat_kritikalitas_aset ?? 'normal';
                                        $kritClass = match($krit) {
                                            'kritis' => 'bg-red-100 text-red-700',
                                            'penting' => 'bg-orange-100 text-orange-700',
                                            default => 'bg-gray-100 text-gray-600'
                                        };
                                    @endphp
                                    <span class="inline-flex px-2 py-1 rounded-md text-xs font-semibold {{ $kritClass }}">
                                        {{ ucfirst($krit) }}
                                    </span>
                                </td>

                                {{-- ACTION --}}
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <div class="inline-flex items-center gap-3">
                                        <a href="{{ route('manage-access-point.edit', $ap->id) }}" class="text-gray-500 hover:text-[#004A54] font-semibold">
                                            Edit
                                        </a>
                                        <form action="{{ route('manage-access-point.destroy', $ap->id) }}" method="POST" onsubmit="return confirm('Hapus access point ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-500 hover:text-red-600 font-semibold">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            {{-- EMPTY STATE --}}
                            <tr>
                                <td colspan="10" class="p-12 text-center text-gray-400">
                                    <div class="text-lg font-semibold mb-1">
                                        Belum ada data Access Point
                                    </div>
                                    <div class="text-sm">
                                        Data Access Point akan tampil di sini sesuai dengan template aset.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if(isset($accessPoints) && method_exists($accessPoints, 'total'))
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-between">
                    <div class="text-xs text-gray-500 font-medium">
                        Menampilkan
                        {{ $accessPoints->firstItem() ?? 0 }}
                        -
                        {{ $accessPoints->lastItem() ?? 0 }}
                        dari
                        {{ $accessPoints->total() }}
                        aset
                    </div>
                    <div>
                        {{ $accessPoints->onEachSide(1)->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection