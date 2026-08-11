@extends('layouts.app', [
    'title' => ($router ? 'Edit Router' : 'Tambah Router') . ' — PLN Financial'
])

@section('content')

<main class="p-6 lg:p-10 space-y-5 w-full">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-sm">
        <a href="{{ route('manage-router') }}"
           class="text-gray-400 dark:text-gray-500 hover:text-[#004A54] dark:hover:text-accent-400 transition-colors">
            Router
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
            {{ $router ? 'Edit Router' : 'Tambah Router' }}
        </span>
    </nav>


    {{-- Title --}}
    <div>
        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
            Manage Asset
        </p>

        <h1 class="text-2xl font-bold text-pln-800 dark:text-white">
            {{ $router ? 'Edit Router' : 'Tambah Router' }}
        </h1>

        <p class="mt-1 text-sm text-gray-400 dark:text-gray-500">
            {{ $router
                ? 'Perbarui informasi Router sesuai standar template aset.'
                : 'Tambahkan informasi Router baru sesuai standar template aset.'
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


    {{-- FORM --}}
    <form
        method="POST"
        action="{{ $router
            ? route('manage-router.update', $router->id)
            : route('manage-router.store')
        }}"
        class="bg-white dark:bg-gray-800 rounded-xl
               border border-gray-200 dark:border-gray-700
               shadow-sm"
    >

        @csrf

        @if ($router)
            @method('PATCH')
        @endif


        {{-- Card Header --}}
        <div class="flex items-start gap-4 p-8 pb-6">

            <div class="w-11 h-11 rounded-lg bg-gray-100 dark:bg-gray-700
                        flex items-center justify-center shrink-0">

                <svg class="w-5 h-5 text-gray-600 dark:text-gray-300"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M4.5 6.75h15m-15 0A2.25 2.25 0 0 1 6.75 4.5h10.5a2.25 2.25 0 0 1 2.25 2.25m-15 0v10.5A2.25 2.25 0 0 0 6.75 19.5h10.5a2.25 2.25 0 0 0 2.25-2.25V6.75M8.25 9.75h.01M8.25 13.5h.01M8.25 17.25h.01" />

                </svg>

            </div>

            <div>
                <h2 class="text-base font-bold text-pln-800 dark:text-white">
                    Formulir Atribut Aset Router
                </h2>

                <p class="text-sm text-gray-400 dark:text-gray-500">
                    Lengkapi atribut umum dan atribut spesifik router sesuai dengan template master data aset.
                </p>
            </div>

        </div>


        {{-- FIELDS CONTAINER --}}
        <div class="border-t border-gray-100 dark:border-gray-700 p-8 space-y-8">


            {{-- SECTION 1: ATRIBUT UMUM --}}
            <div class="space-y-4">
                <h3 class="text-sm font-bold text-[#004A54] dark:text-accent-400 uppercase tracking-wider">
                    I. Atribut Umum
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    {{-- ID ASET --}}
                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            ID Aset <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="id_aset"
                            value="{{ old('id_aset', $router->id_aset ?? '') }}"
                            required
                            placeholder="Contoh: LOGAPPLC2024UPTBK0001"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                   text-gray-800 dark:text-white placeholder-gray-400
                                   focus:border-[#004A54] focus:outline-none
                                   focus:ring-1 focus:ring-[#004A54]"
                        >
                        <span class="text-[10px] text-gray-400 mt-1 block">Primary key 21 digit (Otomatis/Manual)</span>
                    </div>

                    {{-- TANGGAL MULAI AKTIF --}}
                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Tanggal Mulai Aktif / Perolehan
                        </label>
                        <input
                            type="date"
                            name="tanggal_mulai_aktif"
                            value="{{ old('tanggal_mulai_aktif', optional($router->tanggal_mulai_aktif ?? null)->format('Y-m-d')) }}"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                   text-gray-800 dark:text-white
                                   focus:border-[#004A54] focus:outline-none
                                   focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>

                    {{-- STATUS KEPEMILIKAN --}}
                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Status Kepemilikan <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="status_kepemilikan"
                            required
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                   text-gray-800 dark:text-white focus:border-[#004A54]
                                   focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                        >
                            <option value="">— Pilih Status Kepemilikan —</option>
                            @foreach([
                                'pembelian oleh PLN pusat' => 'Pembelian oleh PLN Pusat',
                                'pembelian oleh Unit' => 'Pembelian oleh Unit',
                                'sewa/managed service PLN pusat ke ICON plus' => 'Sewa/Managed Service PLN Pusat ke ICON Plus',
                                'sewa/managed service Unit ke ICON plus' => 'Sewa/Managed Service Unit ke ICON Plus',
                                'sewa/managed service PLN pusat ke vendor lain' => 'Sewa/Managed Service PLN Pusat ke Vendor Lain',
                                'sewa/managed service Unit ke vendor lain' => 'Sewa/Managed Service Unit ke Vendor Lain',
                                'BYOD' => 'BYOD',
                                'milik pihak ketiga' => 'Milik Pihak Ketiga'
                            ] as $val => $label)
                                <option value="{{ $val }}" @selected(old('status_kepemilikan', $router->status_kepemilikan ?? '') == $val)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                {{-- KETERANGAN STATUS KEPEMILIKAN & KLASIFIKASI KEAMANAN --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Keterangan Status Kepemilikan
                        </label>
                        <input
                            type="text"
                            name="keterangan_status_kepemilikan"
                            value="{{ old('keterangan_status_kepemilikan', $router->keterangan_status_kepemilikan ?? '') }}"
                            placeholder="Nama Unit pembeli/penyewa atau nama vendor terkait"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                   text-gray-800 dark:text-white placeholder-gray-400
                                   focus:border-[#004A54] focus:outline-none
                                   focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Klasifikasi Keamanan Aset <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="klasifikasi_keamanan"
                            required
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                   text-gray-800 dark:text-white focus:border-[#004A54]
                                   focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                        >
                            <option value="">— Pilih Klasifikasi Keamanan —</option>
                            <option value="rahasia" @selected(old('klasifikasi_keamanan', $router->klasifikasi_keamanan ?? '') == 'rahasia')>Rahasia</option>
                            <option value="terbatas" @selected(old('klasifikasi_keamanan', $router->klasifikasi_keamanan ?? '') == 'terbatas')>Terbatas</option>
                            <option value="publik" @selected(old('klasifikasi_keamanan', $router->klasifikasi_keamanan ?? '') == 'publik')>Publik</option>
                        </select>
                    </div>
                </div>

                {{-- STATUS KONDISI, OPERASIONAL & KRITIKALITAS --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Status Kondisi Aset <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="status_kondisi"
                            required
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                   text-gray-800 dark:text-white focus:border-[#004A54]
                                   focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                        >
                            <option value="">— Pilih Kondisi —</option>
                            <option value="baik" @selected(old('status_kondisi', $router->status_kondisi ?? '') == 'baik')>Baik</option>
                            <option value="rusak dapat digunakan" @selected(old('status_kondisi', $router->status_kondisi ?? '') == 'rusak dapat digunakan')>Rusak Dapat Digunakan</option>
                            <option value="rusak tidak dapat digunakan" @selected(old('status_kondisi', $router->status_kondisi ?? '') == 'rusak tidak dapat digunakan')>Rusak Tidak Dapat Digunakan</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Status Operasional Aset <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="status_operasional"
                            required
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                   text-gray-800 dark:text-white focus:border-[#004A54]
                                   focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                        >
                            <option value="">— Pilih Status Operasional —</option>
                            <option value="aktif" @selected(old('status_operasional', $router->status_operasional ?? '') == 'aktif')>Aktif</option>
                            <option value="non aktif" @selected(old('status_operasional', $router->status_operasional ?? '') == 'non aktif')>Non Aktif</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Tingkat Kritikalitas Aset <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="tingkat_kritikalitas"
                            required
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                   text-gray-800 dark:text-white focus:border-[#004A54]
                                   focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                        >
                            <option value="">— Pilih Kritikalitas —</option>
                            <option value="kritis" @selected(old('tingkat_kritikalitas', $router->tingkat_kritikalitas ?? '') == 'kritis')>Kritis</option>
                            <option value="penting" @selected(old('tingkat_kritikalitas', $router->tingkat_kritikalitas ?? '') == 'penting')>Penting</option>
                            <option value="pendukung" @selected(old('tingkat_kritikalitas', $router->tingkat_kritikalitas ?? '') == 'pendukung')>Pendukung</option>
                        </select>
                    </div>
                </div>

                {{-- DESKRIPSI TUJUAN / FUNGSI --}}
                <div>
                    <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                        Deskripsi Tujuan, Peran, atau Fungsi Aset
                    </label>
                    <textarea
                        name="deskripsi_tujuan"
                        rows="2"
                        placeholder="Jelaskan tujuan atau fungsi aset (terutama untuk kritikalitas Penting/Kritis)..."
                        class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                               text-gray-800 dark:text-white placeholder-gray-400
                               focus:border-[#004A54] focus:outline-none
                               focus:ring-1 focus:ring-[#004A54]"
                    >{{ old('deskripsi_tujuan', $router->deskripsi_tujuan ?? '') }}</textarea>
                </div>

                {{-- LOKASI & KETERANGAN LOKASI --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Lokasi Aset Saat Ini (Kode Unit) <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="lokasi_aset_saat_ini"
                            value="{{ old('lokasi_aset_saat_ini', $router->lokasi_aset_saat_ini ?? '') }}"
                            required
                            placeholder="Contoh: UPT Bandung"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                   text-gray-800 dark:text-white placeholder-gray-400
                                   focus:border-[#004A54] focus:outline-none
                                   focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Keterangan Lokasi Aset
                        </label>
                        <input
                            type="text"
                            name="keterangan_lokasi"
                            value="{{ old('keterangan_lokasi', $router->keterangan_lokasi ?? '') }}"
                            placeholder="Gedung, lantai, dan ruang detail"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                   text-gray-800 dark:text-white placeholder-gray-400
                                   focus:border-[#004A54] focus:outline-none
                                   focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>
                </div>

                {{-- AUDIT / PENCATAT --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Tanggal Pemeriksaan Terakhir
                        </label>
                        <input
                            type="date"
                            name="tanggal_pemeriksaan_terakhir"
                            value="{{ old('tanggal_pemeriksaan_terakhir', optional($router->tanggal_pemeriksaan_terakhir ?? null)->format('Y-m-d')) }}"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                   text-gray-800 dark:text-white
                                   focus:border-[#004A54] focus:outline-none
                                   focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            PIC Pencatat
                        </label>
                        <input
                            type="text"
                            name="pic_pencatat"
                            value="{{ old('pic_pencatat', $router->pic_pencatat ?? '') }}"
                            placeholder="Nama personil pencatat"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                   text-gray-800 dark:text-white placeholder-gray-400
                                   focus:border-[#004A54] focus:outline-none
                                   focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Bidang Pencatat Aset
                        </label>
                        <input
                            type="text"
                            name="bidang_pencatat_aset"
                            value="{{ old('bidang_pencatat_aset', $router->bidang_pencatat_aset ?? '') }}"
                            placeholder="Nama bidang di DIV STI"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                   text-gray-800 dark:text-white placeholder-gray-400
                                   focus:border-[#004A54] focus:outline-none
                                   focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>
                </div>

            </div>


            <hr class="border-gray-100 dark:border-gray-700">


            {{-- SECTION 2: ATRIBUT SPESIFIK ROUTER --}}
            <div class="space-y-4">
                <h3 class="text-sm font-bold text-[#004A54] dark:text-accent-400 uppercase tracking-wider">
                    II. Atribut Spesifik Router
                </h3>

                {{-- ROW S1: MERK, MODEL, SERIAL NUMBER --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Merk <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="merk"
                            value="{{ old('merk', $router->merk ?? '') }}"
                            required
                            placeholder="Contoh: Cisco"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                   text-gray-800 dark:text-white placeholder-gray-400
                                   focus:border-[#004A54] focus:outline-none
                                   focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Model <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="model"
                            value="{{ old('model', $router->model ?? '') }}"
                            required
                            placeholder="Contoh: Cisco ISR 4331"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                   text-gray-800 dark:text-white placeholder-gray-400
                                   focus:border-[#004A54] focus:outline-none
                                   focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Serial Number <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="serial_number"
                            value="{{ old('serial_number', $router->serial_number ?? '') }}"
                            required
                            placeholder="Serial number dari produsen"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                   text-gray-800 dark:text-white placeholder-gray-400
                                   focus:border-[#004A54] focus:outline-none
                                   focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>
                </div>

                {{-- ROW S2: MAC, IP, PORT --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            MAC Address
                        </label>
                        <input
                            type="text"
                            name="mac_address"
                            value="{{ old('mac_address', $router->mac_address ?? '') }}"
                            placeholder="AA:4C:CC:AC:EE:FF"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                   text-gray-800 dark:text-white placeholder-gray-400
                                   focus:border-[#004A54] focus:outline-none
                                   focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            IP Address / DHCP
                        </label>
                        <input
                            type="text"
                            name="ip_address"
                            value="{{ old('ip_address', $router->ip_address ?? '') }}"
                            placeholder="10.10.10.1 atau DHCP"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                   text-gray-800 dark:text-white placeholder-gray-400
                                   focus:border-[#004A54] focus:outline-none
                                   focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Jumlah Kecepatan Jenis Port
                        </label>
                        <input
                            type="text"
                            name="jumlah_kecepatan_jenis_port"
                            value="{{ old('jumlah_kecepatan_jenis_port', $router->jumlah_kecepatan_jenis_port ?? '') }}"
                            placeholder="Contoh: 8x port 10/100/1000 Mbps"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                   text-gray-800 dark:text-white placeholder-gray-400
                                   focus:border-[#004A54] focus:outline-none
                                   focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>
                </div>

                {{-- ROW S3: PROTOCOL, FIRMWARE, KONSUMSI DAYA --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Protocol Disupport
                        </label>
                        <input
                            type="text"
                            name="protocol_disupport"
                            value="{{ old('protocol_disupport', $router->protocol_disupport ?? '') }}"
                            placeholder="Contoh: RIP, OSPF, BGP, MPLS"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                   text-gray-800 dark:text-white placeholder-gray-400
                                   focus:border-[#004A54] focus:outline-none
                                   focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Versi Firmware/OS
                        </label>
                        <input
                            type="text"
                            name="versi_firmware_os"
                            value="{{ old('versi_firmware_os', $router->versi_firmware_os ?? '') }}"
                            placeholder="Contoh: IOS-XE 17.3"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                   text-gray-800 dark:text-white placeholder-gray-400
                                   focus:border-[#004A54] focus:outline-none
                                   focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Konsumsi Daya (Watt)
                        </label>
                        <input
                            type="number"
                            name="konsumsi_daya"
                            value="{{ old('konsumsi_daya', $router->konsumsi_daya ?? '') }}"
                            placeholder="Contoh: 150"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                   text-gray-800 dark:text-white placeholder-gray-400
                                   focus:border-[#004A54] focus:outline-none
                                   focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>
                </div>

                {{-- ROW S4: RACK, GARANSI, KETERANGAN TAMBAHAN --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Rack (Identitas Rak)
                        </label>
                        <input
                            type="text"
                            name="rack"
                            value="{{ old('rack', $router->rack ?? '') }}"
                            placeholder="Nama/nomor rak perangkat"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                   text-gray-800 dark:text-white placeholder-gray-400
                                   focus:border-[#004A54] focus:outline-none
                                   focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Masa Berlaku Garansi
                        </label>
                        <input
                            type="date"
                            name="masa_berlaku_garansi"
                            value="{{ old('masa_berlaku_garansi', optional($router->masa_berlaku_garansi ?? null)->format('Y-m-d')) }}"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                   text-gray-800 dark:text-white
                                   focus:border-[#004A54] focus:outline-none
                                   focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Keterangan Tambahan
                        </label>
                        <input
                            type="text"
                            name="keterangan"
                            value="{{ old('keterangan', $router->keterangan ?? '') }}"
                            placeholder="Catatan tambahan lain..."
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                   text-gray-800 dark:text-white placeholder-gray-400
                                   focus:border-[#004A54] focus:outline-none
                                   focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>
                </div>

            </div>


            {{-- INFO NOTE --}}
            <div class="flex items-start gap-3 rounded-lg
                        bg-cyan-50 dark:bg-cyan-900/20
                        border border-cyan-100 dark:border-cyan-900/40
                        px-4 py-3">

                <svg class="w-4 h-4 text-cyan-600 dark:text-cyan-400 mt-0.5 shrink-0"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <circle cx="12" cy="12" r="9" stroke-linecap="round" stroke-linejoin="round" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8h.01M11 12h1v4h1" />
                </svg>

                <p class="text-xs text-cyan-800 dark:text-cyan-300">
                    Pastikan seluruh isian formulir mematuhi standar format data master aset PLN agar siap diekspor maupun divalidasi ke sistem laporan keuangan/aset.
                </p>

            </div>

        </div>


        {{-- BUTTONS --}}
        <div class="flex justify-end gap-3 px-8 py-5
                    border-t border-gray-100 dark:border-gray-700">

            <a
                href="{{ route('manage-router') }}"
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
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.25h13.5v13.5H5.25z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 5.25v4.5h7.5v-4.5" />
                </svg>

                {{ $router ? 'Simpan Perubahan' : 'Simpan Router' }}
            </button>

        </div>

    </form>

</main>

@endsection