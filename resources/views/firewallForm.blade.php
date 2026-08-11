@extends('layouts.app', ['title' => ($asset ? 'Edit Firewall' : 'Tambah Firewall') . ' — PLN Financial'])

@section('content')
{{-- Tambahkan CDN CSS Tom Select untuk Dropdown Searchable --}}
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
                <input type="text" placeholder="Cari aset firewall atau data..."
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
                        <p class="text-sm font-semibold text-pln-800 dark:text-white">{{ auth()->user()->name ?? 'Administrator' }}</p>
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-accent-500">
                            {{ auth()->user()->role ?? 'Admin' }}
                        </p>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-pln-800 text-white flex items-center justify-center font-bold text-xs uppercase shrink-0">
                        {{ implode('', array_map(fn($w) => $w[0] ?? '', array_slice(explode(' ', auth()->user()->name ?? 'Admin'), 0, 2))) }}
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
                <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $asset ? 'Edit Firewall' : 'Tambah Firewall' }}</span>
            </nav>

            <h1 class="text-2xl font-bold text-pln-800 dark:text-white">{{ $asset ? 'Edit Aset Firewall' : 'Tambah Aset Firewall Baru' }}</h1>

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
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-pln-800 dark:text-white">Formulir Atribut Aset Firewall</h2>
                        <p class="text-sm text-gray-400 dark:text-gray-500">Lengkapi Atribut Umum dan Atribut Spesifik perangkat Firewall sesuai standar pengelolaan aset.</p>
                    </div>
                </div>

                <div class="border-t border-gray-100 dark:border-gray-700 p-8 space-y-8">

                    {{-- SECTION 1: ATRIBUT UMUM --}}
                    <div>
                        <h3 class="text-sm font-bold uppercase tracking-wider text-[#004A54] dark:text-accent-400 mb-4 pb-2 border-b border-gray-100 dark:border-gray-700">
                            Atribut Umum
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {{-- ID Aset --}}
                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">ID Aset (Otomatis / Manual)</label>
                                <input type="text" name="id_aset" value="{{ old('id_aset', $asset->id_aset ?? '') }}" placeholder="Contoh: 21 digit key aset"
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                            </div>

                            {{-- Tanggal Mulai Aktif / Perolehan Aset Pertama Kali --}}
                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Tanggal Mulai Aktif / Perolehan</label>
                                <input type="date" name="tanggal_mulai_aktif" value="{{ old('tanggal_mulai_aktif', $asset->tanggal_mulai_aktif ?? '') }}"
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                            </div>

                            {{-- Status Kepemilikan --}}
                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Status Kepemilikan</label>
                                <select name="status_kepemilikan"
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                                    <option value="">— Pilih Status Kepemilikan —</option>
                                    <option value="pembelian oleh PLN pusat" @selected(old('status_kepemilikan', $asset->status_kepemilikan ?? '') == 'pembelian oleh PLN pusat')>pembelian oleh PLN pusat</option>
                                    <option value="pembelian oleh Unit" @selected(old('status_kepemilikan', $asset->status_kepemilikan ?? '') == 'pembelian oleh Unit')>pembelian oleh Unit</option>
                                    <option value="sewa/managed service PLN pusat ke ICON plus" @selected(old('status_kepemilikan', $asset->status_kepemilikan ?? '') == 'sewa/managed service PLN pusat ke ICON plus')>sewa/managed service PLN pusat ke ICON plus</option>
                                    <option value="sewa/managed service Unit ke ICON plus" @selected(old('status_kepemilikan', $asset->status_kepemilikan ?? '') == 'sewa/managed service Unit ke ICON plus')>sewa/managed service Unit ke ICON plus</option>
                                    <option value="sewa/managed service PLN pusat ke vendor lain" @selected(old('status_kepemilikan', $asset->status_kepemilikan ?? '') == 'sewa/managed service PLN pusat ke vendor lain')>sewa/managed service PLN pusat ke vendor lain</option>
                                    <option value="BYOD" @selected(old('status_kepemilikan', $asset->status_kepemilikan ?? '') == 'BYOD')>BYOD</option>
                                    <option value="milik pihak ketiga" @selected(old('status_kepemilikan', $asset->status_kepemilikan ?? '') == 'milik pihak ketiga')>milik pihak ketiga</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
                            {{-- Keterangan Status Kepemilikan --}}
                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Ket. Status Kepemilikan</label>
                                <input type="text" name="ket_status_kepemilikan" value="{{ old('ket_status_kepemilikan', $asset->ket_status_kepemilikan ?? '') }}" placeholder="Nama unit / vendor"
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                            </div>

                            {{-- Status Kondisi Aset --}}
                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Status Kondisi Aset</label>
                                <select name="status_kondisi_aset"
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                                    <option value="">— Pilih Kondisi —</option>
                                    <option value="baik" @selected(old('status_kondisi_aset', $asset->status_kondisi_aset ?? '') == 'baik')>baik</option>
                                    <option value="rusak dapat digunakan" @selected(old('status_kondisi_aset', $asset->status_kondisi_aset ?? '') == 'rusak dapat digunakan')>rusak dapat digunakan</option>
                                    <option value="rusak tidak dapat digunakan" @selected(old('status_kondisi_aset', $asset->status_kondisi_aset ?? '') == 'rusak tidak dapat digunakan')>rusak tidak dapat digunakan</option>
                                </select>
                            </div>

                            {{-- Status Operasional Aset --}}
                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Status Operasional Aset</label>
                                <select name="status_operasional_aset"
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                                    <option value="">— Pilih Operasional —</option>
                                    <option value="aktif" @selected(old('status_operasional_aset', $asset->status_operasional_aset ?? '') == 'aktif')>aktif</option>
                                    <option value="non aktif" @selected(old('status_operasional_aset', $asset->status_operasional_aset ?? '') == 'non aktif')>non aktif</option>
                                </select>
                            </div>

                            {{-- Tingkat Kritikalitas Aset --}}
                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Tingkat Kritikalitas Aset</label>
                                <select name="tingkat_kritikalitas_aset"
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                                    <option value="">— Pilih Kritikalitas —</option>
                                    <option value="kritis" @selected(old('tingkat_kritikalitas_aset', $asset->tingkat_kritikalitas_aset ?? '') == 'kritis')>kritis</option>
                                    <option value="penting" @selected(old('tingkat_kritikalitas_aset', $asset->tingkat_kritikalitas_aset ?? '') == 'penting')>penting</option>
                                    <option value="pendukung" @selected(old('tingkat_kritikalitas_aset', $asset->tingkat_kritikalitas_aset ?? '') == 'pendukung')>pendukung</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                            {{-- Klasifikasi Keamanan Aset --}}
                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Klasifikasi Keamanan Aset</label>
                                <select name="klasifikasi_keamanan_aset"
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                                    <option value="">— Pilih Klasifikasi Keamanan —</option>
                                    <option value="rahasia" @selected(old('klasifikasi_keamanan_aset', $asset->klasifikasi_keamanan_aset ?? '') == 'rahasia')>rahasia</option>
                                    <option value="terbatas" @selected(old('klasifikasi_keamanan_aset', $asset->klasifikasi_keamanan_aset ?? '') == 'terbatas')>terbatas</option>
                                    <option value="publik" @selected(old('klasifikasi_keamanan_aset', $asset->klasifikasi_keamanan_aset ?? '') == 'publik')>publik</option>
                                </select>
                            </div>

                            {{-- Lokasi Aset Saat Ini --}}
                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Lokasi Aset Saat Ini (Kode Unit)</label>
                                <input type="text" name="lokasi_aset_saat_ini" value="{{ old('lokasi_aset_saat_ini', $asset->lokasi_aset_saat_ini ?? '') }}" placeholder="Contoh: Kode Unit"
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                            </div>

                            {{-- Tanggal Pemeriksaan Terakhir --}}
                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Tanggal Pemeriksaan Terakhir</label>
                                <input type="date" name="tanggal_pemeriksaan_terakhir" value="{{ old('tanggal_pemeriksaan_terakhir', $asset->tanggal_pemeriksaan_terakhir ?? '') }}"
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            {{-- Deskripsi Tujuan, Peran, atau Fungsi Aset --}}
                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Deskripsi Tujuan / Peran / Fungsi Aset</label>
                                <textarea name="deskripsi_tujuan" rows="2" placeholder="Jelaskan tujuan atau fungsi firewall..."
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">{{ old('deskripsi_tujuan', $asset->deskripsi_tujuan ?? '') }}</textarea>
                            </div>

                            {{-- Keterangan Lokasi Aset --}}
                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Keterangan Lokasi Aset (Gedung, Lantai, Ruang)</label>
                                <textarea name="keterangan_lokasi_aset" rows="2" placeholder="Contoh: Gedung A, Lantai 2, Ruang Server"
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">{{ old('keterangan_lokasi_aset', $asset->keterangan_lokasi_aset ?? '') }}</textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            {{-- PIC Pencatat --}}
                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">PIC Pencatat</label>
                                <input type="text" name="pic_pencatat" value="{{ old('pic_pencatat', $asset->pic_pencatat ?? '') }}" placeholder="Nama personil yang mencatat"
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                            </div>

                            {{-- Bidang Pencatat Aset --}}
                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Bidang Pencatat Aset</label>
                                <input type="text" name="bidang_pencatat_aset" value="{{ old('bidang_pencatat_aset', $asset->bidang_pencatat_aset ?? '') }}" placeholder="Contoh: DIV STI"
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 2: ATRIBUT SPESIFIK FIREWALL --}}
                    <div>
                        <h3 class="text-sm font-bold uppercase tracking-wider text-[#004A54] dark:text-accent-400 mb-4 pb-2 border-b border-gray-100 dark:border-gray-700">
                            Atribut Spesifik Firewall
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            {{-- Merk --}}
                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Merk *</label>
                                <input type="text" name="merk" value="{{ old('merk', $asset->merk ?? '') }}" required placeholder="Contoh: Fortinet, Cisco"
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                            </div>

                            {{-- Model --}}
                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Model *</label>
                                <input type="text" name="model" value="{{ old('model', $asset->model ?? '') }}" required placeholder="Contoh: FortiGate 100F"
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                            </div>

                            {{-- Serial Number --}}
                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Serial Number</label>
                                <input type="text" name="serial_number" value="{{ old('serial_number', $asset->serial_number ?? '') }}" placeholder="Nomor seri perangkat"
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                            </div>

                            {{-- Versi Firmware/OS --}}
                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Versi Firmware/OS *</label>
                                <input type="text" name="versi_firmware" value="{{ old('versi_firmware', $asset->versi_firmware ?? '') }}" required placeholder="Contoh: v7.2.4"
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            {{-- Segmen Number --}}
                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Segmen Number *</label>
                                <textarea name="segmen_number" rows="2" required placeholder="Daftar zona atau segment jaringan dari produsen, bukan dibuat sendiri"
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">{{ old('segmen_number', $asset->segmen_number ?? '') }}</textarea>
                            </div>

                            {{-- Segmen Tujuan --}}
                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Segmen Tujuan *</label>
                                <textarea name="segmen_tujuan" rows="2" required placeholder="Daftar zona atau segment jaringan yang dituju oleh traffic, sesuai fungsi utama firewall"
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">{{ old('segmen_tujuan', $asset->segmen_tujuan ?? '') }}</textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
                            {{-- Konsumsi Daya --}}
                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Konsumsi Daya (Watt)</label>
                                <input type="number" step="0.01" name="konsumsi_daya" value="{{ old('konsumsi_daya', $asset->konsumsi_daya ?? '') }}" placeholder="Contoh: 150"
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                            </div>

                            {{-- Rack --}}
                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Rack / Identitas Penempatan</label>
                                <input type="text" name="rack" value="{{ old('rack', $asset->rack ?? '') }}" placeholder="Contoh: Rack 04"
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                            </div>

                            {{-- Masa Berlaku Garansi --}}
                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Masa Berlaku Garansi</label>
                                <input type="date" name="masa_berlaku_garansi" value="{{ old('masa_berlaku_garansi', $asset->masa_berlaku_garansi ?? '') }}"
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                            </div>

                            {{-- Keterangan --}}
                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Keterangan Tambahan</label>
                                <input type="text" name="keterangan" value="{{ old('keterangan', $asset->keterangan ?? '') }}" placeholder="Penjelasan lain jika perlu"
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                            </div>
                        </div>
                    </div>

                    {{-- Info box --}}
                    <div class="flex items-start gap-3 rounded-lg bg-cyan-50 dark:bg-cyan-900/20 border border-cyan-100 dark:border-cyan-900/40 px-4 py-3">
                        <svg class="w-4 h-4 text-cyan-600 dark:text-cyan-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <circle cx="12" cy="12" r="9" stroke-linecap="round" stroke-linejoin="round" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8h.01M11 12h1v4h1" />
                        </svg>
                        <p class="text-xs text-cyan-800 dark:text-cyan-300">Pastikan atribut bertanda bintang (*) diisi dengan benar sesuai data spesifikasi teknis dari pabrikan/vendor.</p>
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
@endsection