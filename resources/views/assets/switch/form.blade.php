@extends('layouts.app', [
    'title' => ($switch ? 'Edit Switch' : 'Tambah Switch') . ' — PLN Financial'
])

@section('content')

<main class="p-6 lg:p-10 space-y-5 w-full">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-sm">
        <a href="{{ route('manage-switch') }}"
           class="text-gray-400 dark:text-gray-500 hover:text-[#004A54] dark:hover:text-accent-400 transition-colors">
            Switch
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
            {{ $switch ? 'Edit Switch' : 'Tambah Switch' }}
        </span>
    </nav>


    {{-- Title --}}
    <div>
        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
            Manage Asset
        </p>

        <h1 class="text-2xl font-bold text-pln-800 dark:text-white">
            {{ $switch ? 'Edit Switch' : 'Tambah Switch' }}
        </h1>

        <p class="mt-1 text-sm text-gray-400 dark:text-gray-500">
            {{ $switch
                ? 'Perbarui informasi Switch dan TOR Switch.'
                : 'Tambahkan informasi Switch dan TOR Switch baru sesuai standar template asset.'
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
        action="{{ $switch
            ? route('manage-switch.update', $switch->id)
            : route('manage-switch.store')
        }}"
        class="bg-white dark:bg-gray-800 rounded-xl
               border border-gray-200 dark:border-gray-700
               shadow-sm"
    >

        @csrf

        @if ($switch)
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
                          d="M4.5 6.75h15m-15 0A2.25 2.25 0 0 1 6.75 4.5h10.5a2.25 2.25 0 0 1 2.25 2.25m-15 0v10.5A2.25 2.25 0 0 0 6.75 19.5h10.5a2.25 2.25 0 0 0 2.25-2.25V6.75" />

                </svg>

            </div>

            <div>
                <h2 class="text-base font-bold text-pln-800 dark:text-white">
                    Informasi Switch & TOR Switch
                </h2>

                <p class="text-sm text-gray-400 dark:text-gray-500">
                    Lengkapi atribut umum dan spesifik sesuai standar template Manage Asset Switch.
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

                    {{-- ROW 1: ID Aset, Tanggal Perolehan, Status Kepemilikan --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- ID ASET --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                ID Aset <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="id_aset"
                                value="{{ old('id_aset', $switch->id_aset ?? '') }}"
                                required
                                placeholder="Contoh: FISSWTCH2022100000010000"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white placeholder-gray-400
                                       focus:border-[#004A54] focus:outline-none
                                       focus:ring-1 focus:ring-[#004A54]"
                            >
                        </div>

                        {{-- TANGGAL PEROLEHAN --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Tanggal Mulai Aktif / Perolehan Aset <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="date"
                                name="tanggal_mulai_aktif"
                                value="{{ old('tanggal_mulai_aktif', optional($switch->tanggal_mulai_aktif ?? null)->format('Y-m-d')) }}"
                                required
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
                                <option value="pembelian oleh PLN pusat" @selected(old('status_kepemilikan', $switch->status_kepemilikan ?? '') == 'pembelian oleh PLN pusat')>Pembelian oleh PLN pusat</option>
                                <option value="pembelian oleh Unit" @selected(old('status_kepemilikan', $switch->status_kepemilikan ?? '') == 'pembelian oleh Unit')>Pembelian oleh Unit</option>
                                <option value="sewa/managed service PLN pusat ke ICON plus" @selected(old('status_kepemilikan', $switch->status_kepemilikan ?? '') == 'sewa/managed service PLN pusat ke ICON plus')>Sewa/Managed Service PLN Pusat ke ICON Plus</option>
                                <option value="sewa/managed service Unit ke ICON plus" @selected(old('status_kepemilikan', $switch->status_kepemilikan ?? '') == 'sewa/managed service Unit ke ICON plus')>Sewa/Managed Service Unit ke ICON Plus</option>
                                <option value="sewa/managed service PLN pusat ke vendor lain" @selected(old('status_kepemilikan', $switch->status_kepemilikan ?? '') == 'sewa/managed service PLN pusat ke vendor lain')>Sewa/Managed Service PLN Pusat ke Vendor Lain</option>
                                <option value="sewa/managed service Unit ke vendor lain" @selected(old('status_kepemilikan', $switch->status_kepemilikan ?? '') == 'sewa/managed service Unit ke vendor lain')>Sewa/Managed Service Unit ke Vendor Lain</option>
                                <option value="BYOD" @selected(old('status_kepemilikan', $switch->status_kepemilikan ?? '') == 'BYOD')>BYOD</option>
                                <option value="milik pihak ketiga" @selected(old('status_kepemilikan', $switch->status_kepemilikan ?? '') == 'milik pihak ketiga')>Milik Pihak Ketiga</option>
                            </select>
                        </div>

                    </div>


                    {{-- ROW 2: Keterangan Status Kepemilikan, Kondisi, Operasional --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- KETERANGAN STATUS KEPEMILIKAN --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Keterangan Status Kepemilikan
                            </label>
                            <input
                                type="text"
                                name="keterangan_status_kepemilikan"
                                value="{{ old('keterangan_status_kepemilikan', $switch->keterangan_status_kepemilikan ?? '') }}"
                                placeholder="Nama unit/vendor jika relevan"
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
                                name="status_kondisi"
                                required
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white focus:border-[#004A54]
                                       focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                            >
                                <option value="">— Pilih Kondisi —</option>
                                <option value="baik" @selected(old('status_kondisi', $switch->status_kondisi ?? '') == 'baik')>Baik</option>
                                <option value="rusak dapat digunakan" @selected(old('status_kondisi', $switch->status_kondisi ?? '') == 'rusak dapat digunakan')>Rusak Dapat Digunakan</option>
                                <option value="rusak tidak dapat digunakan" @selected(old('status_kondisi', $switch->status_kondisi ?? '') == 'rusak tidak dapat digunakan')>Rusak Tidak Dapat Digunakan</option>
                            </select>
                        </div>

                        {{-- STATUS OPERASIONAL --}}
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
                                <option value="">— Pilih Status —</option>
                                <option value="aktif" @selected(old('status_operasional', $switch->status_operasional ?? '') == 'aktif')>Aktif</option>
                                <option value="non aktif" @selected(old('status_operasional', $switch->status_operasional ?? '') == 'non aktif')>Non Aktif</option>
                            </select>
                        </div>

                    </div>


                    {{-- ROW 3: Kritikalitas, Keamanan, Deskripsi Tujuan --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- KRITIKALITAS --}}
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
                                <option value="kritis" @selected(old('tingkat_kritikalitas', $switch->tingkat_kritikalitas ?? '') == 'kritis')>Kritis</option>
                                <option value="penting" @selected(old('tingkat_kritikalitas', $switch->tingkat_kritikalitas ?? '') == 'penting')>Penting</option>
                                <option value="pendukung" @selected(old('tingkat_kritikalitas', $switch->tingkat_kritikalitas ?? '') == 'pendukung')>Pendukung</option>
                            </select>
                        </div>

                        {{-- KLASIFIKASI KEAMANAN --}}
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
                                <option value="">— Pilih Klasifikasi —</option>
                                <option value="rahasia" @selected(old('klasifikasi_keamanan', $switch->klasifikasi_keamanan ?? '') == 'rahasia')>Rahasia</option>
                                <option value="terbatas" @selected(old('klasifikasi_keamanan', $switch->klasifikasi_keamanan ?? '') == 'terbatas')>Terbatas</option>
                                <option value="publik" @selected(old('klasifikasi_keamanan', $switch->klasifikasi_keamanan ?? '') == 'publik')>Publik</option>
                            </select>
                        </div>

                        {{-- LOKASI ASET SAAT INI (KODE UNIT) --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Lokasi Aset Saat Ini (Kode Unit) <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="lokasi_aset_saat_ini"
                                value="{{ old('lokasi_aset_saat_ini', $switch->lokasi_aset_saat_ini ?? '') }}"
                                required
                                placeholder="Contoh: 10000001"
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
                                Deskripsi Tujuan, Peran, atau Fungsi
                            </label>
                            <input
                                type="text"
                                name="deskripsi_tujuan"
                                value="{{ old('deskripsi_tujuan', $switch->deskripsi_tujuan ?? '') }}"
                                placeholder="Contoh: Switch distribusi jaringan kantor"
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
                                value="{{ old('keterangan_lokasi_aset', $switch->keterangan_lokasi_aset ?? '') }}"
                                placeholder="Contoh: Lantai 2, Ruangan Server"
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
                                value="{{ old('tanggal_pemeriksaan_terakhir', optional($switch->tanggal_pemeriksaan_terakhir ?? null)->format('Y-m-d')) }}"
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

                        {{-- PIC Pencatat --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                PIC Pencatat <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="pic_pencatat" 
                                {{-- PERUBAHAN DI SINI: Tambahkan fallback ke nama user yang login --}}
                                value="{{ old('pic_pencatat', $switch->pic_pencatat ?? auth()->user()->name) }}" 
                                required 
                                placeholder="Nama personil yang mencatat"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                            >
                        </div>

                        {{-- BIDANG PENCATAT ASET --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Bidang Pencatat Aset <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="bidang_pencatat_aset"
                                value="{{ old('bidang_pencatat_aset', $switch->bidang_pencatat_aset ?? '') }}"
                                required
                                placeholder="Contoh: Bidang Operasional TI Jawa Bali Nusa Tenggara"
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


            {{-- BAGIAN 2: ATRIBUT SPESIFIK SWITCH & TOR SWITCH --}}
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-4">
                    Atribut Spesifik Switch & TOR Switch
                </h3>

                <div class="space-y-6">

                    {{-- ROW 1: Merk, Model, Fungsi Switch --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- MERK --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Merk <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="merk"
                                value="{{ old('merk', $switch->merk ?? '') }}"
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
                                value="{{ old('model', $switch->model ?? '') }}"
                                required
                                placeholder="Contoh: Catalyst 9600"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white placeholder-gray-400
                                       focus:border-[#004A54] focus:outline-none
                                       focus:ring-1 focus:ring-[#004A54]"
                            >
                        </div>

                        {{-- FUNGSI SWITCH --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Fungsi Switch <span class="text-red-500">*</span>
                            </label>
                            <select
                                name="fungsi_switch"
                                required
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white focus:border-[#004A54]
                                       focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                            >
                                <option value="">— Pilih Fungsi Switch —</option>
                                <option value="core switch" @selected(old('fungsi_switch', $switch->fungsi_switch ?? '') == 'core switch')>Core Switch</option>
                                <option value="distribution switch" @selected(old('fungsi_switch', $switch->fungsi_switch ?? '') == 'distribution switch')>Distribution Switch</option>
                                <option value="access switch" @selected(old('fungsi_switch', $switch->fungsi_switch ?? '') == 'access switch')>Access Switch</option>
                                <option value="TOR switch" @selected(old('fungsi_switch', $switch->fungsi_switch ?? '') == 'TOR switch')>TOR Switch</option>
                                <option value="lainnya" @selected(old('fungsi_switch', $switch->fungsi_switch ?? '') == 'lainnya')>Lainnya</option>
                            </select>
                        </div>

                    </div>


                    {{-- ROW 2: Serial Number, MAC Address, IP Address --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- SERIAL NUMBER --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Serial Number <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="serial_number"
                                value="{{ old('serial_number', $switch->serial_number ?? '') }}"
                                required
                                placeholder="Serial number dari produsen"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white placeholder-gray-400
                                       focus:border-[#004A54] focus:outline-none
                                       focus:ring-1 focus:ring-[#004A54]"
                            >
                        </div>

                        {{-- MAC ADDRESS --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                MAC Address
                            </label>
                            <input
                                type="text"
                                name="mac_address"
                                value="{{ old('mac_address', $switch->mac_address ?? '') }}"
                                placeholder="Contoh: AA:4C:CC:AC:EE:FF"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white placeholder-gray-400
                                       focus:border-[#004A54] focus:outline-none
                                       focus:ring-1 focus:ring-[#004A54]"
                            >
                        </div>

                        {{-- IP ADDRESS --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                IP Address
                            </label>
                            <input
                                type="text"
                                name="ip_address"
                                value="{{ old('ip_address', $switch->ip_address ?? '') }}"
                                placeholder="Contoh: 192.168.1.1"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white placeholder-gray-400
                                       focus:border-[#004A54] focus:outline-none
                                       focus:ring-1 focus:ring-[#004A54]"
                            >
                        </div>

                    </div>


                    {{-- ROW 3: Jumlah Kecepatan/Jenis Port, Support PoE, Versi Firmware --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- JUMLAH KECEPATAN / JENIS PORT --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Jumlah Kecepatan & Jenis Port <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="jumlah_kecepatan_jenis_port"
                                value="{{ old('jumlah_kecepatan_jenis_port', $switch->jumlah_kecepatan_jenis_port ?? '') }}"
                                required
                                placeholder="Contoh: 8x port 10/100/1000 Gigabit"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white placeholder-gray-400
                                       focus:border-[#004A54] focus:outline-none
                                       focus:ring-1 focus:ring-[#004A54]"
                            >
                        </div>

                        {{-- SUPPORT POE --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Support PoE <span class="text-red-500">*</span>
                            </label>
                            <select
                                name="support_poe"
                                required
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white focus:border-[#004A54]
                                       focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                            >
                                <option value="">— Pilih Support PoE —</option>
                                <option value="ya" @selected(old('support_poe', $switch->support_poe ?? '') == 'ya')>Ya</option>
                                <option value="tidak" @selected(old('support_poe', $switch->support_poe ?? '') == 'tidak')>Tidak</option>
                            </select>
                        </div>

                        {{-- VERSI FIRMWARE / OS --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Versi Firmware / OS
                            </label>
                            <input
                                type="text"
                                name="versi_firmware"
                                value="{{ old('versi_firmware', $switch->versi_firmware ?? '') }}"
                                placeholder="Contoh: v15.2"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white placeholder-gray-400
                                       focus:border-[#004A54] focus:outline-none
                                       focus:ring-1 focus:ring-[#004A54]"
                            >
                        </div>

                    </div>


                    {{-- ROW 4: Konsumsi Daya, Rack, Masa Berlaku Garansi --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- KONSUMSI DAYA --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Konsumsi Daya (Watt)
                            </label>
                            <input
                                type="number"
                                name="konsumsi_daya"
                                value="{{ old('konsumsi_daya', $switch->konsumsi_daya ?? '') }}"
                                placeholder="Contoh: 300"
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
                                Rack
                            </label>
                            <input
                                type="text"
                                name="rack"
                                value="{{ old('rack', $switch->rack ?? '') }}"
                                placeholder="Contoh: 1A"
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
                                value="{{ old('masa_berlaku_garansi', optional($switch->masa_berlaku_garansi ?? null)->format('Y-m-d')) }}"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                                       text-gray-800 dark:text-white
                                       focus:border-[#004A54] focus:outline-none
                                       focus:ring-1 focus:ring-[#004A54]"
                            >
                        </div>

                    </div>


                    {{-- ROW 5: Keterangan Tambahan --}}
                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Keterangan Tambahan
                        </label>
                        <input
                            type="text"
                            name="keterangan"
                            value="{{ old('keterangan', $switch->keterangan ?? '') }}"
                            placeholder="Penjelasan tambahan jika diperlukan"
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
                    Pastikan seluruh atribut umum dan spesifik Switch / TOR Switch terisi sesuai dengan ketentuan pada template data asset PLN.
                </p>

            </div>

        </div>


        {{-- BUTTONS --}}
        <div class="flex justify-end gap-3 px-8 py-5
                    border-t border-gray-100 dark:border-gray-700">

            <a
                href="{{ route('manage-switch') }}"
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

                {{ $switch ? 'Simpan Perubahan' : 'Simpan Switch' }}

            </button>

        </div>

    </form>

</main>

@endsection