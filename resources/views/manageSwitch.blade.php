@extends('layouts.app', ['title' => 'Manage Switch — PLN Financial'])

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
                <input type="text" placeholder="Cari data switch..."
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
                <span class="font-semibold text-gray-700 dark:text-gray-200">Manage Asset: {{ $currentCategory->name ?? 'Switch & TOR Switch' }}</span>
            </nav>

            {{-- Header Title & Action Buttons --}}
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div>
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wider font-bold">MANAGE ASSET</p>
                    <h1 class="text-xl font-bold text-pln-800 dark:text-white tracking-wide">{{ $currentCategory->name ?? 'Daftar Switch & TOR Switch' }}</h1>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('manage-asset.switch.import') }}" class="inline-flex items-center gap-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                        </svg>
                        Import Data
                    </a>
                    <a href="{{ route('manage-asset.switch.create') }}" class="inline-flex items-center gap-2 bg-[#004A54] text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-[#00363d] transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah Switch
                    </a>
                </div>
            </div>
        </div>

    {{-- FLASH --}}
    @if(session('success'))
        <div class="rounded-md border border-green-200
                    bg-green-50 px-4 py-3
                    text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-md border border-red-200
                    bg-red-50 px-4 py-3
                    text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif


    {{-- SEARCH --}}
    <form
        method="GET"
        action="{{ route('manage-switch') }}"
        class="bg-white rounded-xl
               border border-gray-200
               shadow-sm p-3"
    >

        <div class="flex items-center gap-3">

            <div class="relative flex-1">

                <svg
                    class="absolute left-3 top-1/2
                           -translate-y-1/2
                           w-4 h-4 text-gray-400"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.35-4.35"/>
                </svg>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari ID Aset, merk, model, serial number, IP, lokasi, PIC..."
                    class="w-full rounded-md
                           border border-gray-300
                           pl-10 pr-4 py-2.5
                           text-sm
                           focus:border-[#004A54]
                           focus:outline-none
                           focus:ring-1 focus:ring-[#004A54]"
                >

            </div>

            <button
                type="submit"
                class="px-5 py-2.5
                       rounded-md
                       bg-[#004A54]
                       text-white
                       text-sm font-medium
                       hover:bg-[#00363d]"
            >
                Cari
            </button>

            <a
                href="{{ route('manage-switch') }}"
                class="px-5 py-2.5
                       rounded-md
                       border border-[#004A54]
                       text-[#004A54]
                       text-sm font-medium
                       hover:bg-gray-50"
            >
                Reset
            </a>

        </div>

    </form>


    {{-- TABLE --}}
    <div class="bg-white rounded-xl
                border border-gray-200
                shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm whitespace-nowrap">

                <thead class="bg-gray-50 border-b border-gray-200">

                    <tr class="text-left">
                        <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase">ID Aset / Merk</th>
                        <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase">Serial Number / IP</th>
                        <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase">Lokasi / Rack</th>
                        <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase">Spesifikasi Port</th>
                        <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase">Kondisi & Operasional</th>
                        <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase">Kritikalitas</th>
                        <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase">PIC / Bidang</th>
                        <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase text-right sticky right-0 bg-gray-50 shadow-l">Aksi</th>
                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @forelse($switches as $switch)

                        <tr class="hover:bg-gray-50 transition-colors">

                            {{-- ID Aset & Merk/Model --}}
                            <td class="px-5 py-4">
                                <span class="font-semibold text-[#004A54] block">
                                    {{ $switch->id_aset }}
                                </span>
                                <div class="font-medium text-gray-800 text-xs mt-0.5">{{ $switch->merk }} {{ $switch->model ? '— ' . $switch->model : '' }}</div>
                            </td>

                            {{-- Serial Number & IP/MAC Address --}}
                            <td class="px-5 py-4 font-mono text-xs">
                                <div class="text-gray-700">{{ $switch->serial_number }}</div>
                                <div class="text-gray-400 text-[10px] mt-0.5">{{ $switch->ip_address ?? '-' }}</div>
                            </td>

                            {{-- Lokasi & Rack --}}
                            <td class="px-5 py-4 text-gray-700 text-xs">
                                <div>{{ $switch->lokasi_aset_saat_ini ?? '-' }}</div>
                                <div class="text-[10px] text-gray-400">Rack: {{ $switch->rack ?? '-' }}</div>
                            </td>

                            {{-- Port & Kecepatan --}}
                            <td class="px-5 py-4 text-xs text-gray-600 max-w-xs truncate" title="{{ $switch->jumlah_kecepatan_jenis_port ?? $switch->jumlah_port }}">
                                {{ $switch->jumlah_kecepatan_jenis_port ?? $switch->jumlah_port ?? '-' }}
                                @if(isset($switch->support_poe) && strtoupper($switch->support_poe) === 'YA')
                                    <span class="ml-1 px-1.5 py-0.5 bg-purple-50 text-purple-600 text-[10px] rounded font-semibold">PoE</span>
                                @endif
                            </td>

                            {{-- Kondisi & Operasional --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-1.5">
                                    @php
                                        $kondisi = strtolower($switch->status_kondisi ?? '');
                                        $ops = strtolower($switch->status_operasional ?? '');
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium
                                        {{ $kondisi === 'baik' ? 'bg-green-100 text-green-700' : (str_contains($kondisi, 'rusak') ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                        {{ $switch->status_kondisi ?? 'N/A' }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium
                                        {{ $ops === 'aktif' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $switch->status_operasional ?? 'N/A' }}
                                    </span>
                                </div>
                            </td>

                            {{-- Tingkat Kritikalitas --}}
                            <td class="px-5 py-4">
                                @php
                                    $kritikalitas = strtolower($switch->tingkat_kritikalitas ?? '');
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                    {{ $kritikalitas === 'kritis'
                                        ? 'bg-red-100 text-red-700'
                                        : ($kritikalitas === 'penting'
                                            ? 'bg-yellow-100 text-yellow-700'
                                            : 'bg-gray-100 text-gray-600') }}">
                                    {{ $switch->tingkat_kritikalitas ?? '-' }}
                                </span>
                            </td>

                            {{-- PIC & Bidang Pencatat --}}
                            <td class="px-5 py-4 text-xs text-gray-700">
                                <div>{{ $switch->pic_pencatat ?? '-' }}</div>
                                <div class="text-[10px] text-gray-400">{{ $switch->bidang_pencatat_aset ?? '-' }}</div>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-5 py-4 text-right sticky right-0 bg-white shadow-l">
                                <div class="flex items-center justify-end gap-2">

                                    <a
                                        href="{{ route('manage-switch.edit', $switch->id) }}"
                                        title="Edit"
                                        class="w-9 h-9
                                               flex items-center justify-center
                                               rounded-md
                                               border border-gray-200
                                               text-gray-500
                                               hover:text-[#004A54]
                                               hover:border-[#004A54]"
                                    >
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>

                                    <form
                                        action="{{ route('manage-switch.destroy', $switch->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Hapus switch {{ $switch->id_aset }}?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            title="Hapus"
                                            class="w-9 h-9
                                                   flex items-center justify-center
                                                   rounded-md
                                                   border border-gray-200
                                                   text-gray-500
                                                   hover:text-red-600
                                                   hover:border-red-300"
                                        >
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 7h14M10 11v6M14 11v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3" />
                                            </svg>
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="px-5 py-16 text-center">
                                <div class="text-gray-400">
                                    <svg class="w-10 h-10 mx-auto mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77-1.333.192 3 1.732 3z" />
                                    </svg>
                                    <p>
                                        Belum ada data untuk kategori <strong>Switch & TOR Switch</strong>.
                                    </p>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        <div class="flex items-center justify-between
                    px-5 py-4
                    border-t border-gray-100
                    text-sm text-gray-500">

            <div>
                Menampilkan
                {{ $switches->firstItem() ?? 0 }}
                -
                {{ $switches->lastItem() ?? 0 }}
                dari
                {{ $switches->total() }}
                data
            </div>

            <div>
                {{ $switches->onEachSide(1)->links() }}
            </div>

        </div>

    </div>

</main>

@endsection