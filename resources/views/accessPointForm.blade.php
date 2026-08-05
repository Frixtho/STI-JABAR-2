@extends('layouts.app', ['title' => (isset($accessPoint) ? 'Edit Access Point' : 'Tambah Access Point') . ' — PLN Financial'])

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
        <main class="p-6 lg:p-10 space-y-5 w-full">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-1.5 text-sm">
                <a href="{{ route('manage-access-point') }}" class="text-gray-400 dark:text-gray-500 hover:text-[#004A54] dark:hover:text-accent-400 transition-colors">Manage Access Point</a>
                <svg class="w-3.5 h-3.5 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
                <span class="font-semibold text-gray-700 dark:text-gray-200">{{ isset($accessPoint) ? 'Edit Access Point' : 'Tambah Access Point' }}</span>
            </nav>

            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-pln-800 dark:text-white">{{ isset($accessPoint) ? 'Edit Aset Access Point' : 'Tambah Aset Access Point' }}</h1>
                @if(!isset($accessPoint))
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-cyan-50 text-cyan-700 dark:bg-cyan-900/30 dark:text-cyan-300 border border-cyan-200 dark:border-cyan-800">
                        AP-XXX-202608-001
                    </span>
                @endif
            </div>

            @if ($errors->any())
                <div class="rounded-md border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/30 px-4 py-3 text-sm text-red-700 dark:text-red-400">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ isset($accessPoint) ? route('manage-access-point.update', $accessPoint->id ?? $accessPoint) : route('manage-access-point.store') }}"
                class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                @csrf
                @if (isset($accessPoint)) @method('PATCH') @endif

                {{-- ================= SECTION 1: ATRIBUT UMUM ================= --}}
                <div class="p-8 pb-4">
                    <h2 class="text-xs font-extrabold uppercase tracking-wider text-[#004A54] dark:text-cyan-400 mb-6 pb-2 border-b border-gray-100 dark:border-gray-700">Atribut Umum</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">ID Aset</label>
                            <input type="text" name="id_aset" value="{{ old('id_aset', isset($accessPoint) ? $accessPoint->id_aset : 'AP-XXX-202608-001') }}" required
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                            <p class="mt-1 text-[11px] text-gray-400 dark:text-gray-500">Terbentuk otomatis dari kode lokasi, periode perolehan, dan nomor urut.</p>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Tanggal Perolehan <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_perolehan" value="{{ old('tanggal_perolehan', isset($accessPoint) ? $accessPoint->tanggal_perolehan : date('Y-m-d')) }}" required
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Status Kepemilikan <span class="text-red-500">*</span></label>
                            <select name="status_kepemilikan" required
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                                <option value="">— pilih —</option>
                                <option value="Milik Sendiri" @selected(old('status_kepemilikan', isset($accessPoint) ? $accessPoint->status_kepemilikan : '') == 'Milik Sendiri')>Milik Sendiri</option>
                                <option value="Sewa" @selected(old('status_kepemilikan', isset($accessPoint) ? $accessPoint->status_kepemilikan : '') == 'Sewa')>Sewa</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Keterangan Kepemilikan</label>
                            <input type="text" name="keterangan_kepemilikan" value="{{ old('keterangan_kepemilikan', isset($accessPoint) ? $accessPoint->keterangan_kepemilikan : '') }}" placeholder="Nomor kontrak, vendor, atau dasar kepemilikan."
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Status Kondisi <span class="text-red-500">*</span></label>
                            <select name="status_kondisi" required
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                                <option value="baik" @selected(old('status_kondisi', isset($accessPoint) ? $accessPoint->status_kondisi : 'baik') == 'baik')>baik</option>
                                <option value="rusak ringan" @selected(old('status_kondisi', isset($accessPoint) ? $accessPoint->status_kondisi : '') == 'rusak ringan')>rusak ringan</option>
                                <option value="rusak berat" @selected(old('status_kondisi', isset($accessPoint) ? $accessPoint->status_kondisi : '') == 'rusak berat')>rusak berat</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Status Operasional <span class="text-red-500">*</span></label>
                            <select name="status_operasional" required
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                                <option value="aktif" @selected(old('status_operasional', isset($accessPoint) ? $accessPoint->status_operasional : 'aktif') == 'aktif')>aktif</option>
                                <option value="non-aktif" @selected(old('status_operasional', isset($accessPoint) ? $accessPoint->status_operasional : '') == 'non-aktif')>non-aktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Tingkat Kritikalitas <span class="text-red-500">*</span></label>
                            <select name="tingkat_kritikalitas" required
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                                <option value="penting" @selected(old('tingkat_kritikalitas', isset($accessPoint) ? $accessPoint->tingkat_kritikalitas : 'penting') == 'penting')>penting</option>
                                <option value="sangat penting" @selected(old('tingkat_kritikalitas', isset($accessPoint) ? $accessPoint->tingkat_kritikalitas : '') == 'sangat penting')>sangat penting</option>
                                <option value="biasa" @selected(old('tingkat_kritikalitas', isset($accessPoint) ? $accessPoint->tingkat_kritikalitas : '') == 'biasa')>biasa</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Klasifikasi Keamanan <span class="text-red-500">*</span></label>
                            <select name="klasifikasi_keamanan" required
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                                <option value="terbatas" @selected(old('klasifikasi_keamanan', isset($accessPoint) ? $accessPoint->klasifikasi_keamanan : 'terbatas') == 'terbatas')>terbatas</option>
                                <option value="rahasia" @selected(old('klasifikasi_keamanan', isset($accessPoint) ? $accessPoint->klasifikasi_keamanan : '') == 'rahasia')>rahasia</option>
                                <option value="publik" @selected(old('klasifikasi_keamanan', isset($accessPoint) ? $accessPoint->klasifikasi_keamanan : '') == 'publik')>publik</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Deskripsi Fungsi Aset</label>
                        <textarea name="deskripsi_fungsi_aset" rows="2" placeholder="Deskripsikan fungsi aset..."
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">{{ old('deskripsi_fungsi_aset', isset($accessPoint) ? $accessPoint->deskripsi_fungsi_aset : '') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Lokasi Aset Saat Ini <span class="text-red-500">*</span></label>
                            <input type="text" name="lokasi_aset_saat_ini" value="{{ old('lokasi_aset_saat_ini', isset($accessPoint) ? $accessPoint->lokasi_aset_saat_ini : '') }}" required placeholder="Nama lokasi..."
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Kode Lokasi (untuk ID Aset) <span class="text-red-500">*</span></label>
                            <input type="text" name="kode_lokasi" value="{{ old('kode_lokasi', isset($accessPoint) ? $accessPoint->kode_lokasi : 'XXX') }}" required placeholder="XXX"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                            <p class="mt-1 text-[11px] text-gray-400 dark:text-gray-500">3–4 huruf. Diusulkan otomatis dari nama lokasi.</p>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Keterangan Lokasi</label>
                            <input type="text" name="keterangan_lokasi" value="{{ old('keterangan_lokasi', isset($accessPoint) ? $accessPoint->keterangan_lokasi : '') }}" placeholder="Detail ruangan/lantai..."
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Tanggal Pemeriksaan Terakhir</label>
                            <input type="date" name="tanggal_pemeriksaan_terakhir" value="{{ old('tanggal_pemeriksaan_terakhir', isset($accessPoint) ? $accessPoint->tanggal_pemeriksaan_terakhir : '') }}"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">PIC Pencatat <span class="text-red-500">*</span></label>
                            <input type="text" name="pic_pencatat" value="{{ old('pic_pencatat', isset($accessPoint) ? $accessPoint->pic_pencatat : auth()->user()->name) }}" required
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Bidang Pencatat Aset</label>
                            <input type="text" name="bidang_pencatat_aset" value="{{ old('bidang_pencatat_aset', isset($accessPoint) ? $accessPoint->bidang_pencatat_aset : '') }}" placeholder="Contoh: IT / Telekomunikasi"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                        </div>
                    </div>
                </div>

                {{-- ================= SECTION 2: ATRIBUT SPESIFIK ACCESS POINT ================= --}}
                <div class="p-8 pt-6 border-t border-gray-100 dark:border-gray-700">
                    <h2 class="text-xs font-extrabold uppercase tracking-wider text-[#004A54] dark:text-cyan-400 mb-6 pb-2 border-b border-gray-100 dark:border-gray-700">Atribut Spesifik Access Point</h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Merk <span class="text-red-500">*</span></label>
                            <input type="text" name="merk" value="{{ old('merk', isset($accessPoint) ? $accessPoint->merk : '') }}" required placeholder="Contoh: Cisco / Aruba / Mikrotik"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Model <span class="text-red-500">*</span></label>
                            <input type="text" name="model" value="{{ old('model', isset($accessPoint) ? $accessPoint->model : '') }}" required placeholder="Contoh: AirCheck / AP-303"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Serial Number <span class="text-red-500">*</span></label>
                            <input type="text" name="serial_number" value="{{ old('serial_number', isset($accessPoint) ? $accessPoint->serial_number : '') }}" required placeholder="Nomor seri perangkat..."
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">MAC Address</label>
                            <input type="text" name="mac_address" value="{{ old('mac_address', isset($accessPoint) ? $accessPoint->mac_address : '') }}" placeholder="AA:BB:CC:DD:EE:FF"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">IP Address</label>
                            <input type="text" name="ip_address" value="{{ old('ip_address', isset($accessPoint) ? $accessPoint->ip_address : '') }}" placeholder="10.10.5.21"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Nama SSID</label>
                            <input type="text" name="nama_ssid" value="{{ old('nama_ssid', isset($accessPoint) ? $accessPoint->nama_ssid : '') }}" placeholder="Nama Wi-Fi / SSID..."
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Frekuensi</label>
                            <select name="frekuensi"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                                <option value="">— pilih —</option>
                                <option value="2.4 GHz" @selected(old('frekuensi', isset($accessPoint) ? $accessPoint->frekuensi : '') == '2.4 GHz')>2.4 GHz</option>
                                <option value="5 GHz" @selected(old('frekuensi', isset($accessPoint) ? $accessPoint->frekuensi : '') == '5 GHz')>5 GHz</option>
                                <option value="Dual Band (2.4 / 5 GHz)" @selected(old('frekuensi', isset($accessPoint) ? $accessPoint->frekuensi : '') == 'Dual Band (2.4 / 5 GHz)')>Dual Band (2.4 / 5 GHz)</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Menggunakan PoE</label>
                            <select name="menggunakan_poe"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                                <option value="">— pilih —</option>
                                <option value="Ya" @selected(old('menggunakan_poe', isset($accessPoint) ? $accessPoint->menggunakan_poe : '') == 'Ya')>Ya</option>
                                <option value="Tidak" @selected(old('menggunakan_poe', isset($accessPoint) ? $accessPoint->menggunakan_poe : '') == 'Tidak')>Tidak</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Standar WiFi</label>
                            <select name="standar_wifi"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                                <option value="">— pilih —</option>
                                <option value="Wi-Fi 4 (802.11n)" @selected(old('standar_wifi', isset($accessPoint) ? $accessPoint->standar_wifi : '') == 'Wi-Fi 4 (802.11n)')>Wi-Fi 4 (802.11n)</option>
                                <option value="Wi-Fi 5 (802.11ac)" @selected(old('standar_wifi', isset($accessPoint) ? $accessPoint->standar_wifi : '') == 'Wi-Fi 5 (802.11ac)')>Wi-Fi 5 (802.11ac)</option>
                                <option value="Wi-Fi 6 (802.11ax)" @selected(old('standar_wifi', isset($accessPoint) ? $accessPoint->standar_wifi : '') == 'Wi-Fi 6 (802.11ax)')>Wi-Fi 6 (802.11ax)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Enkripsi WiFi</label>
                            <select name="enkripsi_wifi"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                                <option value="">— pilih —</option>
                                <option value="WPA2-PSK" @selected(old('enkripsi_wifi', isset($accessPoint) ? $accessPoint->enkripsi_wifi : '') == 'WPA2-PSK')>WPA2-PSK</option>
                                <option value="WPA3" @selected(old('enkripsi_wifi', isset($accessPoint) ? $accessPoint->enkripsi_wifi : '') == 'WPA3')>WPA3</option>
                                <option value="WPA2/WPA3 Enterprise" @selected(old('enkripsi_wifi', isset($accessPoint) ? $accessPoint->enkripsi_wifi : '') == 'WPA2/WPA3 Enterprise')>WPA2/WPA3 Enterprise</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Versi Firmware/OS</label>
                            <input type="text" name="versi_firmware" value="{{ old('versi_firmware', isset($accessPoint) ? $accessPoint->versi_firmware : '') }}" placeholder="Versi firmware..."
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Konsumsi Daya</label>
                            <input type="text" name="konsumsi_daya" value="{{ old('konsumsi_daya', isset($accessPoint) ? $accessPoint->konsumsi_daya : '') }}" placeholder="Contoh: 25 W"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Rack</label>
                            <input type="text" name="rack" value="{{ old('rack', isset($accessPoint) ? $accessPoint->rack : '') }}" placeholder="Nama / nomor rack..."
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Masa Berlaku Garansi</label>
                            <input type="date" name="masa_berlaku_garansi" value="{{ old('masa_berlaku_garansi', isset($accessPoint) ? $accessPoint->masa_berlaku_garansi : '') }}"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Keterangan Tambahan</label>
                        <textarea name="keterangan" rows="3" placeholder="Catatan atau keterangan tambahan terkait access point..."
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">{{ old('keterangan', isset($accessPoint) ? $accessPoint->keterangan : '') }}</textarea>
                    </div>

                </div>

                {{-- Footer Action Buttons --}}
                <div class="flex justify-end gap-3 px-8 py-5 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 rounded-b-xl">
                    <a href="{{ route('manage-access-point') }}" class="border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-6 py-2.5 rounded-md text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Batal</a>
                    <button type="submit" class="inline-flex items-center gap-2 bg-[#004A54] text-white px-6 py-2.5 rounded-md text-sm font-medium hover:bg-[#00363d] transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        {{ isset($accessPoint) ? 'Simpan Perubahan' : 'Tambah Aset' }}
                    </button>
                </div>
            </form>

        </main>
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
</script>
@endsection