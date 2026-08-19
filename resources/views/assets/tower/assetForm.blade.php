@extends('layouts.app', ['title' => ($asset ? 'Edit Jalur SUTT' : 'Tambah Jalur SUTT') . ' — PLN Financial'])

@section('content')
{{-- Tambahkan CDN CSS Tom Select --}}
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

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
                <input type="text" placeholder="Cari jalur SUTT atau data..."
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
        <main class="p-6 lg:p-10 space-y-5 w-full">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-1.5 text-sm">
                <a href="{{ route('manage-asset') }}" class="text-gray-400 dark:text-gray-500 hover:text-[#004A54] dark:hover:text-accent-400 transition-colors">Manage Asset</a>
                <svg class="w-3.5 h-3.5 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
                <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $asset ? 'Edit Jalur SUTT' : 'Tambah Jalur SUTT' }}</span>
            </nav>

            <h1 class="text-2xl font-bold text-pln-800 dark:text-white">{{ $asset ? 'Edit Jalur SUTT' : 'Tambah Jalur SUTT Baru' }}</h1>

            @if ($errors->any())
                <div class="rounded-md border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/30 px-4 py-3 text-sm text-red-700 dark:text-red-400">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ $asset ? route('manage-asset.update', $asset) : route('manage-asset.store') }}"
                class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                @csrf
                @if ($asset) @method('PATCH') @endif

                {{-- Card Header --}}
                <div class="flex items-start gap-4 p-8 pb-6">
                    <div class="w-11 h-11 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-pln-800 dark:text-white">Informasi Jalur SUTT</h2>
                        <p class="text-sm text-gray-400 dark:text-gray-500">Lengkapi detail jalur SUTT, tegangan, gardu induk, tower, dan panjang jalur di bawah ini.</p>
                    </div>
                </div>

                <div class="border-t border-gray-100 dark:border-gray-700 p-8 space-y-6">

                    {{-- Nama Jalur & Tegangan --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Nama Jalur (Nama SUTT)</label>
                            <input type="text" name="name" value="{{ old('name', $asset->name ?? '') }}" required placeholder="Contoh: SUTT 150kV Bandung Selatan"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Functloc (Kode Lokasi)</label>
                            <input type="text" name="functloc" value="{{ old('functloc', $asset->functloc ?? '') }}" required placeholder="Contoh: 150KV-BDG-UB"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Tegangan</label>
                            <select name="tegangan" required
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                                <option value="">— Pilih Tegangan —</option>
                                <option value="70 kV" @selected(old('tegangan', $asset->tegangan ?? '') == '70 kV')>70 kV</option>
                                <option value="150 kV" @selected(old('tegangan', $asset->tegangan ?? '') == '150 kV')>150 kV</option>
                                <option value="500 kV" @selected(old('tegangan', $asset->tegangan ?? '') == '500 kV')>500 kV (SUTET)</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">UPT</label>
                            <select name="upt_id" required
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                                <option value="">— Pilih UPT —</option>
                                @foreach ($upts as $upt)
                                    <option value="{{ $upt->id }}" @selected(old('upt_id', $asset->upt_id ?? '') == $upt->id)>
                                        {{ $upt->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Field lainnya seperti Nama Jalur, Functloc, dll -->
                    </div>
                    {{-- GI Awal & GI Akhir (Sudah Searchable) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">GI Awal</label>
                            <select name="gi_awal_id" id="search-gi-awal" required placeholder="— Pilih Gardu Induk Awal —"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white">
                                <option value="">— Pilih Gardu Induk Awal —</option>
                                @foreach ($garduInduks as $gi)
                                    <option value="{{ $gi->id }}" @selected(old('gi_awal_id', $asset->gi_awal_id ?? '') == $gi->id)>
                                        {{ $gi->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">GI Akhir</label>
                            <select name="gi_akhir_id" id="search-gi-akhir" required placeholder="— Pilih Gardu Induk Akhir —"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white">
                                <option value="">— Pilih Gardu Induk Akhir —</option>
                                @foreach ($garduInduks as $gi)
                                    <option value="{{ $gi->id }}" @selected(old('gi_akhir_id', $asset->gi_akhir_id ?? '') == $gi->id)>
                                        {{ $gi->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Jumlah Tower & Panjang (KM) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Jumlah Tower</label>
                            <input type="number" name="jumlah_tower" value="{{ old('jumlah_tower', $asset->jumlah_tower ?? '') }}" min="1" placeholder="Contoh: 45" required
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Panjang (KM)</label>
                            <input type="number" step="0.01" name="panjang_km" value="{{ old('panjang_km', $asset->panjang_km ?? '') }}" placeholder="Contoh: 12.50" required
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                        </div>
                    </div>

                    {{-- Info box --}}
                    <div class="flex items-start gap-3 rounded-lg bg-cyan-50 dark:bg-cyan-900/20 border border-cyan-100 dark:border-cyan-900/40 px-4 py-3">
                        <svg class="w-4 h-4 text-cyan-600 dark:text-cyan-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <circle cx="12" cy="12" r="9" stroke-linecap="round" stroke-linejoin="round" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8h.01M11 12h1v4h1" />
                        </svg>
                        <p class="text-xs text-cyan-800 dark:text-cyan-300">Pastikan data GI Awal dan GI Akhir berbeda serta panjang jalur sesuai dengan pengukuran fisik lapangan untuk perhitungan aset yang akurat.</p>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-end gap-3 px-8 py-5 border-t border-gray-100 dark:border-gray-700">
                    <a href="{{ route('manage-asset') }}" class="border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-6 py-2.5 rounded-md text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Batal</a>
                    <button type="submit" class="inline-flex items-center gap-2 bg-[#004A54] text-white px-6 py-2.5 rounded-md text-sm font-medium hover:bg-[#00363d] transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5v9.75a.75.75 0 0 0 .75.75h6a.75.75 0 0 0 .75-.75V4.5m-9 0h9a1.5 1.5 0 0 1 1.5 1.5v13.5a1.5 1.5 0 0 1-1.5 1.5h-13.5a1.5 1.5 0 0 1-1.5-1.5V7.629c0-.398.158-.78.44-1.06l2.129-2.13c.281-.281.663-.44 1.06-.44Z" />
                        </svg>
                        {{ $asset ? 'Simpan Perubahan' : 'Simpan Asset' }}
                    </button>
                </div>
            </form>
        </main>
    </div>
</div>

{{-- Script Tom Select & Sidebar Toggle --}}
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    // Inisialisasi Tom Select untuk GI Awal & GI Akhir agar bisa Search
    document.addEventListener("DOMContentLoaded", function(){
        new TomSelect("#search-gi-awal", {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });

        new TomSelect("#search-gi-akhir", {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });
    });

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
</script>
@endsection