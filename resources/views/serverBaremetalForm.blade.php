@extends('layouts.app', ['title' => (($asset ?? false) ? 'Edit Server' : 'Tambah Server') . ' — PLN Financial'])

@section('content')
<main class="p-6 lg:p-10 space-y-5 w-full">
    <nav class="flex items-center gap-1.5 text-sm">
        <a href="{{ route('manage-server-baremetal') }}" class="text-gray-400 hover:text-[#004A54]">Server Baremetal</a>
        <svg class="w-3.5 h-3.5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
        <span class="font-semibold text-gray-700">{{ ($asset ?? false) ? 'Edit Server' : 'Tambah Server' }}</span>
    </nav>

    <div>
        <h1 class="text-2xl font-bold text-pln-800">{{ ($asset ?? false) ? 'Edit Aset Server Baremetal' : 'Tambah Aset Server Baremetal Baru' }}</h1>
    </div>

    @if ($errors->any())
        <div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ ($asset ?? false) ? route('manage-server-baremetal.update', $asset->id) : route('manage-server-baremetal.store') }}" class="bg-white rounded-xl border border-gray-200 shadow-sm">
        @csrf
        @if ($asset ?? false) @method('PATCH') @endif

        <div class="p-8 space-y-6">
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#004A54] mb-4 pb-2 border-b">Atribut Umum</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="text-xs font-bold text-gray-700">ID Aset <span class="text-red-500">*</span></label>
                    <input type="text" name="id_aset" value="{{ old('id_aset', $asset->id_aset ?? '') }}" required placeholder="Contoh: 21 digit key aset" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Tanggal Mulai Aktif / Perolehan</label>
                    <input type="date" name="tanggal_perolehan" value="{{ old('tanggal_perolehan', optional($asset->tanggal_perolehan ?? null)->format('Y-m-d')) }}" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Status Kepemilikan</label>
                    <select name="status_kepemilikan" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                        <option value="">— Pilih —</option>
                        <option value="pembelian oleh PLN pusat" @selected(old('status_kepemilikan', $asset->status_kepemilikan ?? '') == 'pembelian oleh PLN pusat')>Pembelian oleh PLN pusat</option>
                        <option value="pembelian oleh Unit" @selected(old('status_kepemilikan', $asset->status_kepemilikan ?? '') == 'pembelian oleh Unit')>Pembelian oleh Unit</option>
                        <option value="sewa/managed service PLN pusat ke ICON plus" @selected(old('status_kepemilikan', $asset->status_kepemilikan ?? '') == 'sewa/managed service PLN pusat ke ICON plus')>Sewa/Managed Service pusat ke ICON</option>
                        <option value="milik pihak ketiga" @selected(old('status_kepemilikan', $asset->status_kepemilikan ?? '') == 'milik pihak ketiga')>Milik pihak ketiga</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-4">
                <div>
                    <label class="text-xs font-bold text-gray-700">Ket. Status Kepemilikan</label>
                    <input type="text" name="keterangan_status_kepemilikan" value="{{ old('keterangan_status_kepemilikan', $asset->keterangan_status_kepemilikan ?? '') }}" placeholder="Nama vendor" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Status Kondisi Aset <span class="text-red-500">*</span></label>
                    <select name="status_kondisi" required class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                        <option value="baik" @selected(old('status_kondisi', $asset->status_kondisi ?? '') == 'baik')>Baik</option>
                        <option value="rusak dapat digunakan" @selected(old('status_kondisi', $asset->status_kondisi ?? '') == 'rusak dapat digunakan')>Rusak dapat digunakan</option>
                        <option value="rusak tidak dapat digunakan" @selected(old('status_kondisi', $asset->status_kondisi ?? '') == 'rusak tidak dapat digunakan')>Rusak tidak dapat digunakan</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Status Operasional <span class="text-red-500">*</span></label>
                    <select name="status_operasional" required class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                        <option value="aktif" @selected(old('status_operasional', $asset->status_operasional ?? '') == 'aktif')>Aktif</option>
                        <option value="non aktif" @selected(old('status_operasional', $asset->status_operasional ?? '') == 'non aktif')>Non Aktif</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Tingkat Kritikalitas <span class="text-red-500">*</span></label>
                    <select name="tingkat_kritikalitas" required class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                        <option value="normal" @selected(old('tingkat_kritikalitas', $asset->tingkat_kritikalitas ?? '') == 'normal')>Normal</option>
                        <option value="penting" @selected(old('tingkat_kritikalitas', $asset->tingkat_kritikalitas ?? '') == 'penting')>Penting</option>
                        <option value="kritis" @selected(old('tingkat_kritikalitas', $asset->tingkat_kritikalitas ?? '') == 'kritis')>Kritis</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                <div>
                    <label class="text-xs font-bold text-gray-700">Klasifikasi Keamanan</label>
                    <select name="klasifikasi_keamanan" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                        <option value="internal" @selected(old('klasifikasi_keamanan', $asset->klasifikasi_keamanan ?? '') == 'internal')>Internal</option>
                        <option value="rahasia" @selected(old('klasifikasi_keamanan', $asset->klasifikasi_keamanan ?? '') == 'rahasia')>Rahasia</option>
                        <option value="terbatas" @selected(old('klasifikasi_keamanan', $asset->klasifikasi_keamanan ?? '') == 'terbatas')>Terbatas</option>
                        <option value="publik" @selected(old('klasifikasi_keamanan', $asset->klasifikasi_keamanan ?? '') == 'publik')>Publik</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Lokasi Aset Saat Ini (Kode) <span class="text-red-500">*</span></label>
                    <input type="text" name="lokasi_aset_saat_ini" value="{{ old('lokasi_aset_saat_ini', $asset->lokasi_aset_saat_ini ?? '') }}" required class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Tanggal Pemeriksaan Terakhir</label>
                    <input type="date" name="tanggal_pemeriksaan_terakhir" value="{{ old('tanggal_pemeriksaan_terakhir', optional($asset->tanggal_pemeriksaan_terakhir ?? null)->format('Y-m-d')) }}" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                <div>
                    <label class="text-xs font-bold text-gray-700">Deskripsi / Peran Aset</label>
                    <textarea name="deskripsi_tujuan" rows="2" class="mt-1.5 w-full rounded-md border border-gray-300 py-2 px-3 text-sm">{{ old('deskripsi_tujuan', $asset->deskripsi_tujuan ?? '') }}</textarea>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Keterangan Lokasi Aset</label>
                    <textarea name="keterangan_lokasi_aset" rows="2" class="mt-1.5 w-full rounded-md border border-gray-300 py-2 px-3 text-sm">{{ old('keterangan_lokasi_aset', $asset->keterangan_lokasi_aset ?? '') }}</textarea>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">PIC Pencatat <span class="text-red-500">*</span></label>
                    <input type="text" name="pic_pencatat" value="{{ old('pic_pencatat', $asset->pic_pencatat ?? auth()->user()->name) }}" required class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                    <label class="text-xs font-bold text-gray-700 mt-2 block">Bidang Pencatat Aset</label>
                    <input type="text" name="bidang_pencatat_aset" value="{{ old('bidang_pencatat_aset', $asset->bidang_pencatat_aset ?? '') }}" class="mt-1 w-full rounded-md border border-gray-300 py-2 px-3 text-sm">
                </div>
            </div>

            {{-- BAGIAN 2: ATRIBUT SPESIFIK SERVER BAREMETAL --}}
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#004A54] mt-8 mb-4 pb-2 border-b">Atribut Spesifik Server</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label class="text-xs font-bold text-gray-700">Hostname</label>
                    <input type="text" name="hostname" value="{{ old('hostname', $asset->hostname ?? '') }}" placeholder="Contoh: SRV-APP-01" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Merk <span class="text-red-500">*</span></label>
                    <input type="text" name="merk" value="{{ old('merk', $asset->merk ?? '') }}" required placeholder="Contoh: Dell, HP" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Model <span class="text-red-500">*</span></label>
                    <input type="text" name="model" value="{{ old('model', $asset->model ?? '') }}" required placeholder="Contoh: PowerEdge R740" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Serial Number</label>
                    <input type="text" name="serial_number" value="{{ old('serial_number', $asset->serial_number ?? '') }}" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-4">
                <div>
                    <label class="text-xs font-bold text-gray-700">CPU</label>
                    <input type="text" name="cpu" value="{{ old('cpu', $asset->cpu ?? '') }}" placeholder="Contoh: 2x Intel Xeon Gold" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">RAM</label>
                    <input type="text" name="ram" value="{{ old('ram', $asset->ram ?? '') }}" placeholder="Contoh: 128GB DDR4" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Disk Array</label>
                    <input type="text" name="disk_array" value="{{ old('disk_array', $asset->disk_array ?? '') }}" placeholder="Contoh: RAID 5" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Kapasitas Total Storage</label>
                    <input type="text" name="kapasitas_total_storage" value="{{ old('kapasitas_total_storage', $asset->kapasitas_total_storage ?? '') }}" placeholder="Contoh: 4TB SSD" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-4">
                <div>
                    <label class="text-xs font-bold text-gray-700">MAC Address</label>
                    <input type="text" name="mac_address" value="{{ old('mac_address', $asset->mac_address ?? '') }}" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Sistem Operasi</label>
                    <input type="text" name="sistem_operasi" value="{{ old('sistem_operasi', $asset->sistem_operasi ?? '') }}" placeholder="Contoh: Windows Server 2019" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Security Update OS</label>
                    <input type="text" name="security_update_os" value="{{ old('security_update_os', $asset->security_update_os ?? '') }}" placeholder="Contoh: Q3 2023" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Aplikasi</label>
                    <input type="text" name="aplikasi" value="{{ old('aplikasi', $asset->aplikasi ?? '') }}" placeholder="Aplikasi yang running" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-4">
                <div>
                    <label class="text-xs font-bold text-gray-700">Konsumsi Daya (Watt)</label>
                    <input type="number" step="0.01" name="konsumsi_daya" value="{{ old('konsumsi_daya', $asset->konsumsi_daya ?? '') }}" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Rack</label>
                    <input type="text" name="rack" value="{{ old('rack', $asset->rack ?? '') }}" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Masa Berlaku Garansi</label>
                    <input type="date" name="masa_berlaku_garansi" value="{{ old('masa_berlaku_garansi', optional($asset->masa_berlaku_garansi ?? null)->format('Y-m-d')) }}" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
                </div>
            </div>
            
            <div class="mt-4">
                <label class="text-xs font-bold text-gray-700">Keterangan Tambahan</label>
                <input type="text" name="keterangan" value="{{ old('keterangan', $asset->keterangan ?? '') }}" class="mt-1.5 w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm">
            </div>
        </div>

        <div class="flex justify-end gap-3 px-8 py-5 border-t bg-gray-50 rounded-b-xl">
            <a href="{{ route('manage-server-baremetal') }}" class="border border-gray-300 text-gray-700 px-6 py-2.5 rounded-md text-sm hover:bg-gray-100">Batal</a>
            <button type="submit" class="bg-[#004A54] text-white px-6 py-2.5 rounded-md text-sm font-medium hover:bg-[#00363d]">
                {{ ($asset ?? false) ? 'Simpan Perubahan' : 'Simpan Server' }}
            </button>
        </div>
    </form>
</main>
@endsection