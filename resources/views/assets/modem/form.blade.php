@extends('layouts.app', ['title' => (($asset ?? false) ? 'Edit Modem' : 'Tambah Modem') . ' — PLN Financial'])

@section('content')
<main class="p-6 lg:p-10 space-y-5 w-full">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-sm">
        <a href="{{ route('manage-modem') }}" class="text-gray-400 hover:text-[#004A54]">Modem</a>
        <svg class="w-3.5 h-3.5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
        <span class="font-semibold text-gray-700 dark:text-gray-200">{{ ($asset ?? false) ? 'Edit Modem' : 'Tambah Modem' }}</span>
    </nav>

    <div>
        <h1 class="text-2xl font-bold text-pln-800 dark:text-white">{{ ($asset ?? false) ? 'Edit Aset Modem' : 'Tambah Aset Modem Baru' }}</h1>
        <p class="mt-1 text-sm text-gray-400 dark:text-gray-500">
            {{ ($asset ?? false) ? 'Perbarui informasi perangkat Modem.' : 'Tambahkan informasi perangkat Modem baru sesuai standar template asset.' }}
        </p>
    </div>

    @if ($errors->any())
        <div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ ($asset ?? false) ? route('manage-modem.update', $asset->id) : route('manage-modem.store') }}" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 shadow-sm">
        @csrf
        @if ($asset ?? false) @method('PATCH') @endif

        <div class="p-8 space-y-6">
            {{-- BAGIAN 1: ATRIBUT UMUM --}}
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#004A54] mb-4 pb-2 border-b">Atribut Umum</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="text-xs font-bold text-gray-700">ID Aset (Otomatis / Manual) <span class="text-red-500">*</span></label>
                    <input type="text" name="id_aset" value="{{ old('id_aset', $asset->id_aset ?? '') }}" required placeholder="Contoh: 21 digit key aset" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Tanggal Mulai Aktif / Perolehan Aset</label>
                    <input type="date" name="tanggal_perolehan" value="{{ old('tanggal_perolehan', optional($asset->tanggal_perolehan ?? null)->format('Y-m-d')) }}" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Status Kepemilikan</label>
                    <select name="status_kepemilikan" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                        <option value="">— Pilih Status Kepemilikan —</option>
                        <option value="pembelian oleh PLN pusat" @selected(old('status_kepemilikan', $asset->status_kepemilikan ?? '') == 'pembelian oleh PLN pusat')>pembelian oleh PLN pusat</option>
                        <option value="pembelian oleh Unit" @selected(old('status_kepemilikan', $asset->status_kepemilikan ?? '') == 'pembelian oleh Unit')>pembelian oleh Unit</option>
                        <option value="sewa/managed service PLN pusat ke ICON plus" @selected(old('status_kepemilikan', $asset->status_kepemilikan ?? '') == 'sewa/managed service PLN pusat ke ICON plus')>sewa/managed service PLN pusat ke ICON plus</option>
                        <option value="sewa/managed service Unit ke ICON plus" @selected(old('status_kepemilikan', $asset->status_kepemilikan ?? '') == 'sewa/managed service Unit ke ICON plus')>sewa/managed service Unit ke ICON plus</option>
                        <option value="sewa/managed service PLN pusat ke vendor lain" @selected(old('status_kepemilikan', $asset->status_kepemilikan ?? '') == 'sewa/managed service PLN pusat ke vendor lain')>sewa/managed service PLN pusat ke vendor lain</option>
                        <option value="sewa/managed service Unit ke vendor lain" @selected(old('status_kepemilikan', $asset->status_kepemilikan ?? '') == 'sewa/managed service Unit ke vendor lain')>sewa/managed service Unit ke vendor lain</option>
                        <option value="BYOD" @selected(old('status_kepemilikan', $asset->status_kepemilikan ?? '') == 'BYOD')>BYOD</option>
                        <option value="milik pihak ketiga" @selected(old('status_kepemilikan', $asset->status_kepemilikan ?? '') == 'milik pihak ketiga')>milik pihak ketiga</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-4">
                <div>
                    <label class="text-xs font-bold text-gray-700">Ket. Status Kepemilikan</label>
                    <input type="text" name="keterangan_status_kepemilikan" value="{{ old('keterangan_status_kepemilikan', $asset->keterangan_status_kepemilikan ?? '') }}" placeholder="Nama unit / vendor" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Status Kondisi Aset <span class="text-red-500">*</span></label>
                    <select name="status_kondisi" required class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                        <option value="">— Pilih Kondisi —</option>
                        <option value="baik" @selected(old('status_kondisi', $asset->status_kondisi ?? '') == 'baik')>baik</option>
                        <option value="rusak dapat digunakan" @selected(old('status_kondisi', $asset->status_kondisi ?? '') == 'rusak dapat digunakan')>rusak dapat digunakan</option>
                        <option value="rusak tidak dapat digunakan" @selected(old('status_kondisi', $asset->status_kondisi ?? '') == 'rusak tidak dapat digunakan')>rusak tidak dapat digunakan</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Status Operasional Aset <span class="text-red-500">*</span></label>
                    <select name="status_operasional" required class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                        <option value="">— Pilih Status —</option>
                        <option value="aktif" @selected(old('status_operasional', $asset->status_operasional ?? '') == 'aktif')>aktif</option>
                        <option value="non aktif" @selected(old('status_operasional', $asset->status_operasional ?? '') == 'non aktif')>non aktif</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Tingkat Kritikalitas Aset <span class="text-red-500">*</span></label>
                    <select name="tingkat_kritikalitas" required class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                        <option value="">— Pilih Kritikalitas —</option>
                        <option value="kritis" @selected(old('tingkat_kritikalitas', $asset->tingkat_kritikalitas ?? '') == 'kritis')>kritis</option>
                        <option value="penting" @selected(old('tingkat_kritikalitas', $asset->tingkat_kritikalitas ?? '') == 'penting')>penting</option>
                        <option value="pendukung" @selected(old('tingkat_kritikalitas', $asset->tingkat_kritikalitas ?? '') == 'pendukung')>pendukung</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                <div>
                    <label class="text-xs font-bold text-gray-700">Klasifikasi Keamanan Aset</label>
                    <select name="klasifikasi_keamanan" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                        <option value="">— Pilih Klasifikasi —</option>
                        <option value="rahasia" @selected(old('klasifikasi_keamanan', $asset->klasifikasi_keamanan ?? '') == 'rahasia')>rahasia</option>
                        <option value="terbatas" @selected(old('klasifikasi_keamanan', $asset->klasifikasi_keamanan ?? '') == 'terbatas')>terbatas</option>
                        <option value="publik" @selected(old('klasifikasi_keamanan', $asset->klasifikasi_keamanan ?? '') == 'publik')>publik</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Lokasi Aset Saat Ini (Kode Unit) <span class="text-red-500">*</span></label>
                    <input type="text" name="lokasi_aset_saat_ini" value="{{ old('lokasi_aset_saat_ini', $asset->lokasi_aset_saat_ini ?? '') }}" required placeholder="Contoh: Kode Unit" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Tanggal Pemeriksaan Terakhir</label>
                    <input type="date" name="tanggal_pemeriksaan_terakhir" value="{{ old('tanggal_pemeriksaan_terakhir', optional($asset->tanggal_pemeriksaan_terakhir ?? null)->format('Y-m-d')) }}" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <div>
                    <label class="text-xs font-bold text-gray-700">Deskripsi Tujuan, Peran, atau Fungsi Aset</label>
                    <textarea name="deskripsi_tujuan" rows="2" placeholder="Jelaskan tujuan atau fungsi modem..." class="mt-1.5 w-full rounded-md border border-gray-300 py-2 px-3 text-sm">{{ old('deskripsi_tujuan', $asset->deskripsi_tujuan ?? '') }}</textarea>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Keterangan Lokasi Aset (Gedung, Lantai, Ruang)</label>
                    <textarea name="keterangan_lokasi_aset" rows="2" placeholder="Contoh: Gedung A, Lantai 2" class="mt-1.5 w-full rounded-md border border-gray-300 py-2 px-3 text-sm">{{ old('keterangan_lokasi_aset', $asset->keterangan_lokasi_aset ?? '') }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <div>
                    <label class="text-xs font-bold text-gray-700">PIC Pencatat <span class="text-red-500">*</span></label>
                    <input type="text" name="pic_pencatat" value="{{ old('pic_pencatat', $asset->pic_pencatat ?? auth()->user()->name) }}" required placeholder="Nama personil pencatat" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Bidang Pencatat Aset <span class="text-red-500">*</span></label>
                    <input type="text" name="bidang_pencatat_aset" value="{{ old('bidang_pencatat_aset', $asset->bidang_pencatat_aset ?? '') }}" required placeholder="Contoh: DIV STI" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
            </div>

            {{-- BAGIAN 2: ATRIBUT SPESIFIK MODEM --}}
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#004A54] mt-8 mb-4 pb-2 border-b">Atribut Spesifik Modem</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="text-xs font-bold text-gray-700">Merk <span class="text-red-500">*</span></label>
                    <input type="text" name="merk" value="{{ old('merk', $asset->merk ?? '') }}" required placeholder="Contoh: Cisco, Huawei" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Model <span class="text-red-500">*</span></label>
                    <input type="text" name="model" value="{{ old('model', $asset->model ?? '') }}" required placeholder="Contoh: HG8245H" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Tipe Koneksi <span class="text-red-500">*</span></label>
                    <select name="tipe_koneksi" required class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                        <option value="">— Pilih Tipe Koneksi —</option>
                        <option value="coaxial" @selected(old('tipe_koneksi', $asset->tipe_koneksi ?? '') == 'coaxial')>coaxial</option>
                        <option value="dial up atau PSTN" @selected(old('tipe_koneksi', $asset->tipe_koneksi ?? '') == 'dial up atau PSTN')>dial up atau PSTN</option>
                        <option value="DSL" @selected(old('tipe_koneksi', $asset->tipe_koneksi ?? '') == 'DSL')>DSL</option>
                        <option value="ethernet atau metro E" @selected(old('tipe_koneksi', $asset->tipe_koneksi ?? '') == 'ethernet atau metro E')>ethernet atau metro E</option>
                        <option value="fiber optic" @selected(old('tipe_koneksi', $asset->tipe_koneksi ?? '') == 'fiber optic')>fiber optic</option>
                        <option value="leased line" @selected(old('tipe_koneksi', $asset->tipe_koneksi ?? '') == 'leased line')>leased line</option>
                        <option value="satelit" @selected(old('tipe_koneksi', $asset->tipe_koneksi ?? '') == 'satelit')>satelit</option>
                        <option value="seluler atau wireless" @selected(old('tipe_koneksi', $asset->tipe_koneksi ?? '') == 'seluler atau wireless')>seluler atau wireless</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                <div>
                    <label class="text-xs font-bold text-gray-700">Serial Number</label>
                    <input type="text" name="serial_number" value="{{ old('serial_number', $asset->serial_number ?? '') }}" placeholder="Nomor seri perangkat" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">MAC Address</label>
                    <input type="text" name="mac_address" value="{{ old('mac_address', $asset->mac_address ?? '') }}" placeholder="Contoh: 00:1A:2B:3C:4D:5E" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">IP Address</label>
                    <input type="text" name="ip_address" value="{{ old('ip_address', $asset->ip_address ?? '') }}" placeholder="Contoh: 192.168.1.1" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-4">
                <div>
                    <label class="text-xs font-bold text-gray-700">Versi Firmware / OS <span class="text-red-500">*</span></label>
                    <input type="text" name="versi_firmware" value="{{ old('versi_firmware', $asset->versi_firmware ?? '') }}" required placeholder="Contoh: v1.0" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Konsumsi Daya (Watt)</label>
                    <input type="number" step="0.01" name="konsumsi_daya" value="{{ old('konsumsi_daya', $asset->konsumsi_daya ?? '') }}" placeholder="Contoh: 15" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Rack / Penempatan</label>
                    <input type="text" name="rack" value="{{ old('rack', $asset->rack ?? '') }}" placeholder="Contoh: Rack 1" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Masa Berlaku Garansi</label>
                    <input type="date" name="masa_berlaku_garansi" value="{{ old('masa_berlaku_garansi', optional($asset->masa_berlaku_garansi ?? null)->format('Y-m-d')) }}" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
            </div>

            <div class="mt-4">
                <label class="text-xs font-bold text-gray-700">Keterangan Tambahan</label>
                <input type="text" name="keterangan" value="{{ old('keterangan', $asset->keterangan ?? '') }}" placeholder="Catatan tambahan jika diperlukan" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
            </div>
        </div>

        <div class="flex justify-end gap-3 px-8 py-5 border-t bg-gray-50 rounded-b-xl">
            <a href="{{ route('manage-modem') }}" class="border border-gray-300 text-gray-700 px-6 py-2.5 rounded-md text-sm hover:bg-gray-100">Batal</a>
            <button type="submit" class="bg-[#004A54] text-white px-6 py-2.5 rounded-md text-sm font-medium hover:bg-[#00363d]">
                {{ ($asset ?? false) ? 'Simpan Perubahan' : 'Simpan Modem' }}
            </button>
        </div>
    </form>
</main>
@endsection