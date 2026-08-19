@extends('layouts.app', [
    'title' => ($asset ? 'Edit Access Point' : 'Tambah Access Point') . ' — PLN Financial'
])

@section('content')

<div class="flex-1 min-w-0">

    {{-- ===================== CONTENT ===================== --}}
    <main class="p-6 lg:p-10 space-y-5 w-full">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-1.5 text-sm">
            <a href="{{ route('manage-access-point') }}"
               class="text-gray-400 dark:text-gray-500 hover:text-[#004A54] dark:hover:text-accent-400 transition-colors">
                Access Point
            </a>

            <svg class="w-3.5 h-3.5 text-gray-300 dark:text-gray-600"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>

            <span class="font-semibold text-gray-700 dark:text-gray-200">
                {{ $asset ? 'Edit Access Point' : 'Tambah Access Point' }}
            </span>
        </nav>


        {{-- Title --}}
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                Manage Asset
            </p>

            <h1 class="text-2xl font-bold text-pln-800 dark:text-white">
                {{ $asset ? 'Edit Access Point' : 'Tambah Access Point' }}
            </h1>

            <p class="mt-1 text-sm text-gray-400 dark:text-gray-500">
                {{ $asset
                    ? 'Perbarui informasi Access Point sesuai standar pengelolaan aset.'
                    : 'Tambahkan informasi Access Point baru ke dalam sistem.'
                }}
            </p>
        </div>


        {{-- Error --}}
        @if ($errors->any())
            <div class="rounded-md border border-red-200 dark:border-red-800
                        bg-red-50 dark:bg-red-900/30 px-4 py-3
                        text-sm text-red-700 dark:text-red-400">

                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        @endif


        {{-- ===================== FORM ===================== --}}
        <form
            method="POST"
            action="{{ $asset
                ? route('manage-access-point.update', $asset)
                : route('manage-access-point.store')
            }}"
            class="bg-white dark:bg-gray-800 rounded-xl
                   border border-gray-200 dark:border-gray-700
                   shadow-sm"
        >

            @csrf

            @if ($asset)
                @method('PATCH')
            @endif


            {{-- ===================== SEKSI 1: ATRIBUT UMUM ===================== --}}
            <div class="p-8 pb-4">
                <h2 class="text-base font-bold text-pln-800 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#004A54]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.121 2.121 0 0114.12 24.12L8.29 18.29m3.13-3.12l4-4a2.12 2.12 0 10-3-3l-4 4m3 3l-3-3m0 0l-5.66-5.66a2.121 2.121 0 113-3L11 9.5" />
                    </svg>
                    Atribut Umum Aset
                </h2>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-0.5">
                    Informasi dasar identifikasi, kepemilikan, dan status operasional perangkat.
                </p>
            </div>

            <div class="border-t border-gray-100 dark:border-gray-700 p-8 space-y-6">

                {{-- ROW 1: ID Aset, Tanggal Perolehan, Status Kepemilikan --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            ID Aset <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="id_aset"
                            value="{{ old('id_aset', $asset ? $asset->id_aset : '') }}"
                            required
                            placeholder="Contoh: AP-001"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Tanggal Perolehan Aset
                        </label>
                        <input
                            type="date"
                            name="tanggal_perolehan"
                            value="{{ old('tanggal_perolehan', $asset ? $asset->tanggal_perolehan : '') }}"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Status Kepemilikan <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="status_kepemilikan"
                            required
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                        >
                            <option value="">— Pilih Kepemilikan —</option>
                            <option value="Milik Sendiri" @selected(old('status_kepemilikan', $asset?->status_kepemilikan) == 'Milik Sendiri')>Milik Sendiri</option>
                            <option value="Sewa" @selected(old('status_kepemilikan', $asset?->status_kepemilikan) == 'Sewa')>Sewa</option>
                            <option value="Pinjam Pakai" @selected(old('status_kepemilikan', $asset?->status_kepemilikan) == 'Pinjam Pakai')>Pinjam Pakai</option>
                        </select>
                    </div>
                </div>

                {{-- ROW 2: Kondisi, Operasional, Kritikalitas, Keamanan --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Status Kondisi <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="status_kondisi"
                            required
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                        >
                            <option value="">— Pilih Kondisi —</option>
                            <option value="Baik" @selected(old('status_kondisi', $asset?->status_kondisi) == 'Baik')>Baik</option>
                            <option value="Rusak Ringan" @selected(old('status_kondisi', $asset?->status_kondisi) == 'Rusak Ringan')>Rusak Ringan</option>
                            <option value="Rusak Berat" @selected(old('status_kondisi', $asset?->status_kondisi) == 'Rusak Berat')>Rusak Berat</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Status Operasional <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="status_operasional"
                            required
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                        >
                            <option value="">— Pilih Status —</option>
                            <option value="Aktif" @selected(old('status_operasional', $asset?->status_operasional) == 'Aktif')>Aktif</option>
                            <option value="Tidak Aktif" @selected(old('status_operasional', $asset?->status_operasional) == 'Tidak Aktif')>Tidak Aktif</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Tingkat Kritikalitas Aset
                        </label>
                        <select
                            name="tingkat_kritikalitas_aset"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                        >
                            <option value="">— Pilih Kritikalitas —</option>
                            <option value="Rendah" @selected(old('tingkat_kritikalitas_aset', $asset?->tingkat_kritikalitas_aset) == 'Rendah')>Rendah</option>
                            <option value="Sedang" @selected(old('tingkat_kritikalitas_aset', $asset?->tingkat_kritikalitas_aset) == 'Sedang')>Sedang</option>
                            <option value="Tinggi" @selected(old('tingkat_kritikalitas_aset', $asset?->tingkat_kritikalitas_aset) == 'Tinggi')>Tinggi</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Klasifikasi Keamanan
                        </label>
                        <select
                            name="klasifikasi_keamanan_aset"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                        >
                            <option value="">— Pilih Klasifikasi —</option>
                            <option value="Publik" @selected(old('klasifikasi_keamanan_aset', $asset?->klasifikasi_keamanan_aset) == 'Publik')>Publik</option>
                            <option value="Internal" @selected(old('klasifikasi_keamanan_aset', $asset?->klasifikasi_keamanan_aset) == 'Internal')>Internal</option>
                            <option value="Rahasia" @selected(old('klasifikasi_keamanan_aset', $asset?->klasifikasi_keamanan_aset) == 'Rahasia')>Rahasia</option>
                        </select>
                    </div>
                </div>

                {{-- ROW 3: Lokasi & Keterangan Lokasi --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Lokasi Aset Saat Ini <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="lokasi_aset_saat_ini"
                            value="{{ old('lokasi_aset_saat_ini', $asset ? $asset->lokasi_aset_saat_ini : '') }}"
                            required
                            placeholder="Contoh: ULP Bandung Selatan"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Keterangan Detail Lokasi
                        </label>
                        <input
                            type="text"
                            name="keterangan_lokasi_aset"
                            value="{{ old('keterangan_lokasi_aset', $asset ? $asset->keterangan_lokasi_aset : '') }}"
                            placeholder="Contoh: Lantai 2 Ruang Server Rak 3"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>
                </div>

                {{-- ROW 4: Deskripsi / Fungsi Aset --}}
                <div>
                    <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                        Deskripsi Tujuan, Peran, atau Fungsi Aset
                    </label>
                    <textarea
                        name="deskripsi_tujuan_peran_fungsi_aset"
                        rows="2"
                        placeholder="Jelaskan peran atau fungsi utama Access Point ini..."
                        class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                    >{{ old('deskripsi_tujuan_peran_fungsi_aset', $asset?->deskripsi_tujuan_peran_fungsi_aset) }}</textarea>
                </div>

                {{-- ROW 5: Pemeriksaan, PIC, dan Bidang --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Tanggal Pemeriksaan Terakhir
                        </label>
                        <input
                            type="date"
                            name="tanggal_pemeriksaan_terakhir"
                            value="{{ old('tanggal_pemeriksaan_terakhir', $asset ? $asset->tanggal_pemeriksaan_terakhir : '') }}"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            PIC Pencatat
                        </label>
                        <input
                            type="text"
                            name="pic_pencatat"
                            value="{{ old('pic_pencatat', $asset ? $asset->pic_pencatat : '') }}"
                            placeholder="Nama PIC"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Bidang Pencatat Aset
                        </label>
                        <input
                            type="text"
                            name="bidang_pencatat_aset"
                            value="{{ old('bidang_pencatat_aset', $asset ? $asset->bidang_pencatat_aset : '') }}"
                            placeholder="Contoh: Divisi IT / Telematika"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>
                </div>

            </div>


            {{-- ===================== SEKSI 2: ATRIBUT SPESIFIK ACCESS POINT ===================== --}}
            <div class="border-t border-gray-200 dark:border-gray-700 p-8 pb-4 bg-gray-50/50 dark:bg-gray-800/50">
                <h2 class="text-base font-bold text-pln-800 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#004A54]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Atribut Spesifik Access Point
                </h2>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-0.5">
                    Spesifikasi teknis perangkat keras jaringan nirkabel Access Point.
                </p>
            </div>

            <div class="border-t border-gray-100 dark:border-gray-700 p-8 space-y-6 bg-gray-50/50 dark:bg-gray-800/50">

                {{-- ROW SPEC 1: Merk, Model, Serial Number --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Merk <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="merk"
                            value="{{ old('merk', $asset ? $asset->merk : '') }}"
                            required
                            placeholder="Contoh: Cisco / Aruba / Ubiquiti"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Model <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="model"
                            value="{{ old('model', $asset ? $asset->model : '') }}"
                            required
                            placeholder="Contoh: Aironet 1830"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Serial Number <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="serial_number"
                            value="{{ old('serial_number', $asset ? $asset->serial_number : '') }}"
                            required
                            placeholder="Nomor Seri Perangkat"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>
                </div>

                {{-- ROW SPEC 2: IP Address, MAC Address, Nama SSID --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            IP Address
                        </label>
                        <input
                            type="text"
                            name="ip_address"
                            value="{{ old('ip_address', $asset ? $asset->ip_address : '') }}"
                            placeholder="Contoh: 192.168.1.1"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            MAC Address
                        </label>
                        <input
                            type="text"
                            name="mac_address"
                            value="{{ old('mac_address', $asset ? $asset->mac_address : '') }}"
                            placeholder="00:11:22:33:44:55"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Nama SSID
                        </label>
                        <input
                            type="text"
                            name="nama_ssid"
                            value="{{ old('nama_ssid', $asset ? $asset->nama_ssid : '') }}"
                            placeholder="Contoh: PLN-Internal / Guest-Wifi"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>
                </div>

                {{-- ROW SPEC 3: Frekuensi, Menggunakan PoE, Standar WiFi --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Frekuensi
                        </label>
                        <select
                            name="frekuensi"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                        >
                            <option value="">— Pilih Frekuensi —</option>
                            <option value="2.4 GHz" @selected(old('frekuensi', $asset?->frekuensi) == '2.4 GHz')>2.4 GHz</option>
                            <option value="5 GHz" @selected(old('frekuensi', $asset?->frekuensi) == '5 GHz')>5 GHz</option>
                            <option value="Dual Band (2.4 / 5 GHz)" @selected(old('frekuensi', $asset?->frekuensi) == 'Dual Band (2.4 / 5 GHz)')>Dual Band (2.4 / 5 GHz)</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Menggunakan PoE (Power over Ethernet)
                        </label>
                        <select
                            name="menggunakan_poe"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                        >
                            <option value="">— Pilih Opsi —</option>
                            <option value="Ya" @selected(old('menggunakan_poe', $asset?->menggunakan_poe) == 'Ya')>Ya</option>
                            <option value="Tidak" @selected(old('menggunakan_poe', $asset?->menggunakan_poe) == 'Tidak')>Tidak</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Standar WiFi
                        </label>
                        <input
                            type="text"
                            name="standar_wifi"
                            value="{{ old('standar_wifi', $asset ? $asset->standar_wifi : '') }}"
                            placeholder="Contoh: 802.11ac / Wi-Fi 6 (802.11ax)"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>
                </div>

                {{-- ROW SPEC 4: Enkripsi WiFi, Versi Firmware/OS, Konsumsi Daya --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Enkripsi WiFi
                        </label>
                        <select
                            name="enkripsi_wifi"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                        >
                            <option value="">— Pilih Enkripsi —</option>
                            <option value="WPA2" @selected(old('enkripsi_wifi', $asset?->enkripsi_wifi) == 'WPA2')>WPA2</option>
                            <option value="WPA3" @selected(old('enkripsi_wifi', $asset?->enkripsi_wifi) == 'WPA3')>WPA3</option>
                            <option value="WPA2/WPA3 Enterprise" @selected(old('enkripsi_wifi', $asset?->enkripsi_wifi) == 'WPA2/WPA3 Enterprise')>WPA2/WPA3 Enterprise</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Versi Firmware / OS
                        </label>
                        <input
                            type="text"
                            name="versi_firmware_os"
                            value="{{ old('versi_firmware_os', $asset ? $asset->versi_firmware_os : '') }}"
                            placeholder="Contoh: v8.5.1"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Konsumsi Daya
                        </label>
                        <input
                            type="text"
                            name="konsumsi_daya"
                            value="{{ old('konsumsi_daya', $asset ? $asset->konsumsi_daya : '') }}"
                            placeholder="Contoh: 12W"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>
                </div>

                {{-- ROW SPEC 5: Rack, Masa Berlaku Garansi, Keterangan --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Rack / Penempatan Rak
                        </label>
                        <input
                            type="text"
                            name="rack"
                            value="{{ old('rack', $asset ? $asset->rack : '') }}"
                            placeholder="Contoh: Rack 02"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Masa Berlaku Garansi
                        </label>
                        <input
                            type="date"
                            name="masa_berlaku_garansi"
                            value="{{ old('masa_berlaku_garansi', $asset ? $asset->masa_berlaku_garansi : '') }}"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Keterangan Tambahan
                        </label>
                        <input
                            type="text"
                            name="keterangan"
                            value="{{ old('keterangan', $asset ? $asset->keterangan : '') }}"
                            placeholder="Catatan tambahan lain..."
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>
                </div>

            </div>


            {{-- ===================== BUTTON ===================== --}}
            <div class="flex justify-end gap-3 px-8 py-5
                        border-t border-gray-100 dark:border-gray-700">

                <a
                    href="{{ route('manage-access-point') }}"
                    class="border border-gray-300 dark:border-gray-600
                           text-gray-700 dark:text-gray-300
                           px-6 py-2.5 rounded-md text-sm
                           hover:bg-gray-50 dark:hover:bg-gray-700
                           transition-colors"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center gap-2
                           bg-[#004A54] text-white
                           px-6 py-2.5 rounded-md
                           text-sm font-medium
                           hover:bg-[#00363d]
                           transition-colors"
                >

                    <svg class="w-4 h-4"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M5.25 5.25h13.5v13.5H5.25z" />

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M8.25 5.25v4.5h7.5v-4.5" />

                    </svg>

                    {{ $asset ? 'Simpan Perubahan' : 'Simpan Access Point' }}

                </button>

            </div>

        </form>

    </main>

</div>

@endsection