@extends('layouts.app', [
    'title' => ($asset ?? false ? 'Edit Firewall' : 'Tambah Firewall') . ' — PLN Financial'
])

@section('content')

<main class="p-6 lg:p-10 space-y-5 w-full">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-sm">
        <a href="{{ route('manage-firewall') }}"
           class="text-gray-400 dark:text-gray-500 hover:text-[#004A54] dark:hover:text-accent-400 transition-colors">
            Firewall
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
            {{ ($asset ?? false) ? 'Edit Firewall' : 'Tambah Firewall' }}
        </span>
    </nav>


    {{-- Title --}}
    <div>
        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
            Manage Asset
        </p>

        <h1 class="text-2xl font-bold text-pln-800 dark:text-white">
            {{ ($asset ?? false) ? 'Edit Firewall' : 'Tambah Firewall' }}
        </h1>

        <p class="mt-1 text-sm text-gray-400 dark:text-gray-500">
            {{ ($asset ?? false)
                ? 'Perbarui informasi perangkat Firewall.'
                : 'Tambahkan informasi perangkat Firewall baru sesuai standar template asset.'
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
        action="{{ ($asset ?? false)
            ? route('manage-asset.firewall.update', $asset->id)
            : route('manage-asset.firewall.store')
        }}"
        class="bg-white dark:bg-gray-800 rounded-xl
               border border-gray-200 dark:border-gray-700
               shadow-sm"
    >

        @csrf

        @if ($asset ?? false)
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
                          d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />

                </svg>

            </div>

            <div>
                <h2 class="text-base font-bold text-pln-800 dark:text-white">
                    Informasi Firewall
                </h2>

                <p class="text-sm text-gray-400 dark:text-gray-500">
                    Lengkapi atribut umum dan spesifik sesuai standar template Manage Asset Firewall.
                </p>
            </div>

        </div>


        {{-- FIELDS --}}
        <div class="border-t border-gray-100 dark:border-gray-700 p-8 space-y-6">


            {{-- BAGIAN 1: ATRIBUT UMUM --}}
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-4">
                    Atribut Umum
                </h3>

                <div class="space-y-6">

                    {{-- ROW 1: ID Aset, Tanggal Mulai Aktif, Status Kepemilikan --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- ID ASET --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                ID Aset <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="id_aset"
                                value="{{ old('id_aset', $asset->id_aset ?? '') }}"
                                required
                                placeholder="Contoh: FW-XXX-202608-001"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white placeholder-gray-400
                                       focus:border-[#004A54] focus:outline-none
                                       focus:ring-1 focus:ring-[#004A54]"
                            >
                        </div>

                        {{-- TANGGAL MULAI AKTIF --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Tanggal Mulai Aktif / Perolehan Aset
                            </label>
                            <input
                                type="date"
                                name="tanggal_mulai_aktif"
                                value="{{ old('tanggal_mulai_aktif', optional($asset->tanggal_mulai_aktif ?? null)->format('Y-m-d')) }}"
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
                                Status Kepemilikan
                            </label>
                            <select
                                name="status_kepemilikan"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white focus:border-[#004A54]
                                       focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                            >
                                <option value="">— Pilih Status Kepemilikan —</option>
                                <option value="pembelian oleh PLN pusat" @selected(old('status_kepemilikan', $asset->status_kepemilikan ?? '') == 'pembelian oleh PLN pusat')>Pembelian oleh PLN pusat</option>
                                <option value="pembelian oleh Unit" @selected(old('status_kepemilikan', $asset->status_kepemilikan ?? '') == 'pembelian oleh Unit')>Pembelian oleh Unit</option>
                                <option value="sewa/managed service PLN pusat ke ICON plus" @selected(old('status_kepemilikan', $asset->status_kepemilikan ?? '') == 'sewa/managed service PLN pusat ke ICON plus')>Sewa/Managed Service PLN Pusat ke ICON Plus</option>
                                <option value="sewa/managed service Unit ke ICON plus" @selected(old('status_kepemilikan', $asset->status_kepemilikan ?? '') == 'sewa/managed service Unit ke ICON plus')>Sewa/Managed Service Unit ke ICON Plus</option>
                                <option value="sewa/managed service PLN pusat ke vendor lain" @selected(old('status_kepemilikan', $asset->status_kepemilikan ?? '') == 'sewa/managed service PLN pusat ke vendor lain')>Sewa/Managed Service PLN Pusat ke Vendor Lain</option>
                                <option value="BYOD" @selected(old('status_kepemilikan', $asset->status_kepemilikan ?? '') == 'BYOD')>BYOD</option>
                                <option value="milik pihak ketiga" @selected(old('status_kepemilikan', $asset->status_kepemilikan ?? '') == 'milik pihak ketiga')>Milik Pihak Ketiga</option>
                            </select>
                        </div>

                    </div>


                    {{-- ROW 2: Keterangan Kepemilikan, Kondisi, Operasional --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- KETERANGAN STATUS KEPEMILIKAN --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Ket. Status Kepemilikan
                            </label>
                            <input
                                type="text"
                                name="keterangan_status_kepemilikan"
                                value="{{ old('keterangan_status_kepemilikan', $asset->keterangan_status_kepemilikan ?? '') }}"
                                placeholder="Nama unit / vendor"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white placeholder-gray-400
                                       focus:border-[#004A54] focus:outline-none
                                       focus:ring-1 focus:ring-[#004A54]"
                            >
                        </div>

                        {{-- KONDISI --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Status Kondisi Aset <span class="text-red-500">*</span>
                            </label>
                            <select
                                name="status_kondisi_aset"
                                required
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white focus:border-[#004A54]
                                       focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                            >
                                <option value="">— Pilih Kondisi —</option>
                                <option value="baik" @selected(old('status_kondisi_aset', $asset->status_kondisi_aset ?? '') == 'baik')>Baik</option>
                                <option value="rusak dapat digunakan" @selected(old('status_kondisi_aset', $asset->status_kondisi_aset ?? '') == 'rusak dapat digunakan')>Rusak Dapat Digunakan</option>
                                <option value="rusak tidak dapat digunakan" @selected(old('status_kondisi_aset', $asset->status_kondisi_aset ?? '') == 'rusak tidak dapat digunakan')>Rusak Tidak Dapat Digunakan</option>
                            </select>
                        </div>

                        {{-- STATUS OPERASIONAL --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Status Operasional Aset <span class="text-red-500">*</span>
                            </label>
                            <select
                                name="status_operasional_aset"
                                required
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white focus:border-[#004A54]
                                       focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                            >
                                <option value="">— Pilih Status —</option>
                                <option value="aktif" @selected(old('status_operasional_aset', $asset->status_operasional_aset ?? '') == 'aktif')>Aktif</option>
                                <option value="non aktif" @selected(old('status_operasional_aset', $asset->status_operasional_aset ?? '') == 'non aktif')>Non Aktif</option>
                            </select>
                        </div>

                    </div>


                    {{-- ROW 3: Kritikalitas, Keamanan, Lokasi --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- KRITIKALITAS --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Tingkat Kritikalitas <span class="text-red-500">*</span>
                            </label>
                            <select
                                name="tingkat_kritikalitas_aset"
                                required
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white focus:border-[#004A54]
                                       focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                            >
                                <option value="">— Pilih Kritikalitas —</option>
                                <option value="kritis" @selected(old('tingkat_kritikalitas_aset', $asset->tingkat_kritikalitas_aset ?? '') == 'kritis')>Kritis</option>
                                <option value="penting" @selected(old('tingkat_kritikalitas_aset', $asset->tingkat_kritikalitas_aset ?? '') == 'penting')>Penting</option>
                                <option value="pendukung" @selected(old('tingkat_kritikalitas_aset', $asset->tingkat_kritikalitas_aset ?? '') == 'pendukung')>Pendukung</option>
                            </select>
                        </div>

                        {{-- KLASIFIKASI KEAMANAN --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Klasifikasi Keamanan Aset
                            </label>
                            <select
                                name="klasifikasi_keamanan_aset"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white focus:border-[#004A54]
                                       focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                            >
                                <option value="">— Pilih Klasifikasi —</option>
                                <option value="rahasia" @selected(old('klasifikasi_keamanan_aset', $asset->klasifikasi_keamanan_aset ?? '') == 'rahasia')>Rahasia</option>
                                <option value="terbatas" @selected(old('klasifikasi_keamanan_aset', $asset->klasifikasi_keamanan_aset ?? '') == 'terbatas')>Terbatas</option>
                                <option value="publik" @selected(old('klasifikasi_keamanan_aset', $asset->klasifikasi_keamanan_aset ?? '') == 'publik')>Publik</option>
                            </select>
                        </div>

                        {{-- LOKASI ASET SAAT INI --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Lokasi Aset Saat Ini (Kode Unit) <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="lokasi_aset_saat_ini"
                                value="{{ old('lokasi_aset_saat_ini', $asset->lokasi_aset_saat_ini ?? '') }}"
                                required
                                placeholder="Contoh: UIT Jabar"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white placeholder-gray-400
                                       focus:border-[#004A54] focus:outline-none
                                       focus:ring-1 focus:ring-[#004A54]"
                            >
                        </div>

                    </div>


                    {{-- ROW 4: Deskripsi, Keterangan Lokasi, Tanggal Pemeriksaan --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- DESKRIPSI TUJUAN/PERAN --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Deskripsi Tujuan / Peran / Fungsi
                            </label>
                            <input
                                type="text"
                                name="deskripsi_tujuan_peran_fungsi"
                                value="{{ old('deskripsi_tujuan_peran_fungsi', $asset->deskripsi_tujuan_peran_fungsi ?? '') }}"
                                placeholder="Jelaskan tujuan atau fungsi firewall..."
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white placeholder-gray-400
                                       focus:border-[#004A54] focus:outline-none
                                       focus:ring-1 focus:ring-[#004A54]"
                            >
                        </div>

                        {{-- KETERANGAN LOKASI --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Keterangan Lokasi Aset
                            </label>
                            <input
                                type="text"
                                name="keterangan_lokasi_aset"
                                value="{{ old('keterangan_lokasi_aset', $asset->keterangan_lokasi_aset ?? '') }}"
                                placeholder="Contoh: Gedung A, Lantai 2"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white placeholder-gray-400
                                       focus:border-[#004A54] focus:outline-none
                                       focus:ring-1 focus:ring-[#004A54]"
                            >
                        </div>

                        {{-- TANGGAL PEMERIKSAAN TERAKHIR --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Tanggal Pemeriksaan Terakhir
                            </label>
                            <input
                                type="date"
                                name="tanggal_pemeriksaan_terakhir"
                                value="{{ old('tanggal_pemeriksaan_terakhir', optional($asset->tanggal_pemeriksaan_terakhir ?? null)->format('Y-m-d')) }}"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white
                                       focus:border-[#004A54] focus:outline-none
                                       focus:ring-1 focus:ring-[#004A54]"
                            >
                        </div>

                    </div>


                    {{-- ROW 5: PIC Pencatat & Bidang Pencatat --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- PIC Pencatat (Otomatis User Login) --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                PIC Pencatat <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="pic_pencatat"
                                value="{{ old('pic_pencatat', $asset->pic_pencatat ?? auth()->user()->name) }}"
                                required
                                placeholder="Nama personil yang mencatat"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white placeholder-gray-400
                                       focus:border-[#004A54] focus:outline-none
                                       focus:ring-1 focus:ring-[#004A54]"
                            >
                        </div>

                        {{-- BIDANG PENCATAT ASET --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Bidang Pencatat Aset
                            </label>
                            <input
                                type="text"
                                name="bidang_pencatat_aset"
                                value="{{ old('bidang_pencatat_aset', $asset->bidang_pencatat_aset ?? '') }}"
                                placeholder="Contoh: DIV STI"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white placeholder-gray-400
                                       focus:border-[#004A54] focus:outline-none
                                       focus:ring-1 focus:ring-[#004A54]"
                            >
                        </div>

                    </div>

                </div>
            </div>


            <hr class="border-gray-200 dark:border-gray-700">


            {{-- BAGIAN 2: ATRIBUT SPESIFIK FIREWALL --}}
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-4">
                    Atribut Spesifik Firewall
                </h3>

                <div class="space-y-6">

                    {{-- ROW 1: Merk, Model, Serial Number, Versi Firmware/OS --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                        {{-- MERK --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Merk <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="merk"
                                value="{{ old('merk', $asset->merk ?? '') }}"
                                required
                                placeholder="Contoh: Cisco"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white placeholder-gray-400
                                       focus:border-[#004A54] focus:outline-none
                                       focus:ring-1 focus:ring-[#004A54]"
                            >
                        </div>

                        {{-- MODEL --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Model <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="model"
                                value="{{ old('model', $asset->model ?? '') }}"
                                required
                                placeholder="Contoh: Cisco ISR 4431"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white placeholder-gray-400
                                       focus:border-[#004A54] focus:outline-none
                                       focus:ring-1 focus:ring-[#004A54]"
                            >
                        </div>

                        {{-- SERIAL NUMBER --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Serial Number
                            </label>
                            <input
                                type="text"
                                name="serial_number"
                                value="{{ old('serial_number', $asset->serial_number ?? '') }}"
                                placeholder="Contoh: 09809"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white placeholder-gray-400
                                       focus:border-[#004A54] focus:outline-none
                                       focus:ring-1 focus:ring-[#004A54]"
                            >
                        </div>

                        {{-- VERSI FIRMWARE / OS --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Versi Firmware / OS <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="versi_firmware_os"
                                value="{{ old('versi_firmware_os', $asset->versi_firmware_os ?? '') }}"
                                required
                                placeholder="Contoh: v7.2.4"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white placeholder-gray-400
                                       focus:border-[#004A54] focus:outline-none
                                       focus:ring-1 focus:ring-[#004A54]"
                            >
                        </div>

                    </div>


                    {{-- ROW 2: Segmen Number & Segmen Tujuan --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- SEGMEN NUMBER --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Segmen Number <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                name="segmen_number"
                                rows="2"
                                required
                                placeholder="Masukkan segmen number..."
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white placeholder-gray-400
                                       focus:border-[#004A54] focus:outline-none
                                       focus:ring-1 focus:ring-[#004A54]"
                            >{{ old('segmen_number', $asset->segmen_number ?? '') }}</textarea>
                        </div>

                        {{-- SEGMEN TUJUAN --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Segmen Tujuan <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                name="segmen_tujuan"
                                rows="2"
                                required
                                placeholder="Masukkan segmen tujuan..."
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white placeholder-gray-400
                                       focus:border-[#004A54] focus:outline-none
                                       focus:ring-1 focus:ring-[#004A54]"
                            >{{ old('segmen_tujuan', $asset->segmen_tujuan ?? '') }}</textarea>
                        </div>

                    </div>


                    {{-- ROW 3: Konsumsi Daya, Rack, Masa Berlaku Garansi --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- KONSUMSI DAYA --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Konsumsi Daya (Watt)
                            </label>
                            <input
                                type="number"
                                step="0.01"
                                name="konsumsi_daya"
                                value="{{ old('konsumsi_daya', $asset->konsumsi_daya ?? '') }}"
                                placeholder="Contoh: 150"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white placeholder-gray-400
                                       focus:border-[#004A54] focus:outline-none
                                       focus:ring-1 focus:ring-[#004A54]"
                            >
                        </div>

                        {{-- RACK --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Rack / Identitas Penempatan
                            </label>
                            <input
                                type="text"
                                name="rack"
                                value="{{ old('rack', $asset->rack ?? '') }}"
                                placeholder="Contoh: Rack 04"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white placeholder-gray-400
                                       focus:border-[#004A54] focus:outline-none
                                       focus:ring-1 focus:ring-[#004A54]"
                            >
                        </div>

                        {{-- MASA BERLAKU GARANSI --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Masa Berlaku Garansi
                            </label>
                            <input
                                type="date"
                                name="masa_berlaku_garansi"
                                value="{{ old('masa_berlaku_garansi', optional($asset->masa_berlaku_garansi ?? null)->format('Y-m-d')) }}"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white
                                       focus:border-[#004A54] focus:outline-none
                                       focus:ring-1 focus:ring-[#004A54]"
                            >
                        </div>

                    </div>


                    {{-- ROW 4: Keterangan Tambahan --}}
                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Keterangan Tambahan
                        </label>
                        <input
                            type="text"
                            name="keterangan"
                            value="{{ old('keterangan', $asset->keterangan ?? '') }}"
                            placeholder="Penjelasan lain jika perlu"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                   text-gray-800 dark:text-white placeholder-gray-400
                                   focus:border-[#004A54] focus:outline-none
                                   focus:ring-1 focus:ring-[#004A54]"
                        >
                    </div>

                </div>
            </div>


            {{-- INFO BANNER --}}
            <div class="flex items-start gap-3 rounded-lg
                        bg-cyan-50 dark:bg-cyan-900/20
                        border border-cyan-100 dark:border-cyan-900/40
                        px-4 py-3">

                <svg class="w-4 h-4 text-cyan-600 dark:text-cyan-400 mt-0.5 shrink-0"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">

                    <circle cx="12" cy="12" r="9"
                            stroke-linecap="round"
                            stroke-linejoin="round" />

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 8h.01M11 12h1v4h1" />

                </svg>

                <p class="text-xs text-cyan-800 dark:text-cyan-300">
                    Pastikan atribut bertanda bintang (*) diisi dengan benar sesuai data spesifikasi teknis dari pabrikan/vendor.
                </p>

            </div>

        </div>


        {{-- BUTTONS --}}
        <div class="flex justify-end gap-3 px-8 py-5
                    border-t border-gray-100 dark:border-gray-700">

            <a
                href="{{ route('manage-firewall') }}"
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

                {{ ($asset ?? false) ? 'Simpan Perubahan' : 'Simpan Firewall' }}

            </button>

        </div>

    </form>

</main>

@endsection