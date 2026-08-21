@extends('layouts.app', ['title' => ($asset ? 'Edit ' : 'Tambah ') . $currentCategory->name])

@section('content')
<main class="p-6 lg:p-8 space-y-6 w-full">
    
    {{-- ========================================== --}}
    {{-- 1. BREADCRUMB NAVIGASI --}}
    {{-- ========================================== --}}
    <div class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('manage-asset.generic.index', $currentCategory->slug) }}" class="hover:text-[#004A54] transition-colors uppercase">
            {{ $currentCategory->name }}
        </a>
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-800 font-medium">{{ $asset ? 'Edit ' : 'Tambah ' }} {{ $currentCategory->name }}</span>
    </div>

    {{-- ========================================== --}}
    {{-- 2. HEADER HALAMAN --}}
    {{-- ========================================== --}}
    <div>
        <p class="text-[11px] font-bold text-gray-400 tracking-widest uppercase mb-1">Manage Asset</p>
        <h1 class="text-3xl font-bold text-[#004A54]">{{ $asset ? 'Edit ' : 'Tambah ' }} {{ $currentCategory->name }}</h1>
        <p class="text-sm text-gray-500 mt-1">Tambahkan informasi {{ $currentCategory->name }} baru sesuai standar template asset.</p>
    </div>

    {{-- ========================================== --}}
    {{-- 3. KOTAK FORM (CARD) --}}
    {{-- ========================================== --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mt-4">
        
        {{-- Card Header --}}
        <div class="p-6 border-b border-gray-100 flex items-start gap-4 bg-white">
            <div class="w-10 h-10 rounded-lg bg-gray-50 border border-gray-200 flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-[#004A54]">Informasi {{ $currentCategory->name }}</h2>
                <p class="text-sm text-gray-500 mt-0.5">Lengkapi atribut umum dan spesifik sesuai standar template Manage Asset {{ $currentCategory->name }}.</p>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- FORM UTAMA --}}
        {{-- ========================================== --}}
        <form action="{{ $asset ? route('manage-asset.generic.update', ['category' => $currentCategory->slug, 'id' => $asset->id]) : route('manage-asset.generic.store', $currentCategory->slug) }}" method="POST" class="p-6 lg:p-8">
            @csrf
            @if($asset) @method('PATCH') @endif

            {{-- Hidden Inputs --}}
            <input type="hidden" name="name" id="hidden_name" value="{{ old('name', $asset->name ?? '') }}">
            <input type="hidden" name="upt_id" value="{{ App\Models\Unit::where('level', 2)->first()->id ?? 1 }}">

            {{-- ===================== ATRIBUT UMUM (STATIS) ===================== --}}
            <div class="mb-10">
                <h3 class="text-xs font-bold text-[#004A54] dark:text-accent-400 uppercase tracking-wider mb-6">Atribut Umum</h3>
                
                <div class="space-y-6">
                    
                    {{-- BARIS 1: 3 Kolom --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">ID Aset <span class="text-red-500">*</span></label>
                            <input type="text" name="asset_id" value="{{ old('asset_id', $asset->asset_id ?? '') }}" required placeholder="Contoh: 21 digit key aset" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 py-2.5 px-3 focus:ring-[#004A54]">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Tanggal Mulai Aktif / Perolehan</label>
                            <input type="date" name="acquisition_date" value="{{ old('acquisition_date', $asset->acquisition_date ?? '') }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 py-2.5 px-3 focus:ring-[#004A54]">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Status Kepemilikan</label>
                            <select name="ownership_status" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 py-2.5 px-3 focus:ring-[#004A54]">
                                <option value="" disabled {{ old('ownership_status', $asset->ownership_status ?? '') ? '' : 'selected' }}>— Pilih —</option>
                                <option value="Milik PLN" @selected(old('ownership_status', $asset->ownership_status ?? '') == 'Milik PLN')>Milik PLN</option>
                                <option value="Sewa" @selected(old('ownership_status', $asset->ownership_status ?? '') == 'Sewa')>Sewa</option>
                                <option value="Pinjam Pakai" @selected(old('ownership_status', $asset->ownership_status ?? '') == 'Pinjam Pakai')>Pinjam Pakai</option>
                            </select>
                        </div>
                    </div>

                    {{-- BARIS 2: 4 Kolom --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Ket. Status Kepemilikan</label>
                            <input type="text" name="ownership_desc" value="{{ old('ownership_desc', $asset->ownership_desc ?? '') }}" placeholder="Nama vendor" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 py-2.5 px-3 focus:ring-[#004A54]">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Status Kondisi Aset <span class="text-red-500">*</span></label>
                            <select name="condition_status" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 py-2.5 px-3 focus:ring-[#004A54]">
                                <option value="Baik" @selected(old('condition_status', $asset->condition_status ?? '') == 'Baik' || !isset($asset))>Baik</option>
                                <option value="Rusak" @selected(old('condition_status', $asset->condition_status ?? '') == 'Rusak')>Rusak</option>
                                <option value="Perbaikan" @selected(old('condition_status', $asset->condition_status ?? '') == 'Perbaikan')>Perbaikan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Status Operasional <span class="text-red-500">*</span></label>
                            <select name="operational_status" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 py-2.5 px-3 focus:ring-[#004A54]">
                                <option value="Aktif" @selected(old('operational_status', $asset->operational_status ?? '') == 'Aktif' || !isset($asset))>Aktif</option>
                                <option value="Non-Aktif" @selected(old('operational_status', $asset->operational_status ?? '') == 'Non-Aktif')>Non-Aktif</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Tingkat Kritikalitas <span class="text-red-500">*</span></label>
                            <select name="criticality_level" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 py-2.5 px-3 focus:ring-[#004A54]">
                                <option value="Normal" @selected(old('criticality_level', $asset->criticality_level ?? '') == 'Normal' || !isset($asset))>Normal</option>
                                <option value="Tinggi" @selected(old('criticality_level', $asset->criticality_level ?? '') == 'Tinggi')>Tinggi</option>
                                <option value="Rendah" @selected(old('criticality_level', $asset->criticality_level ?? '') == 'Rendah')>Rendah</option>
                            </select>
                        </div>
                    </div>

                    {{-- BARIS 3: 3 Kolom --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Klasifikasi Keamanan</label>
                            <select name="security_classification" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 py-2.5 px-3 focus:ring-[#004A54]">
                                <option value="Internal" @selected(old('security_classification', $asset->security_classification ?? '') == 'Internal' || !isset($asset))>Internal</option>
                                <option value="Rahasia" @selected(old('security_classification', $asset->security_classification ?? '') == 'Rahasia')>Rahasia</option>
                                <option value="Publik" @selected(old('security_classification', $asset->security_classification ?? '') == 'Publik')>Publik</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Lokasi Aset Saat Ini (Kode) <span class="text-red-500">*</span></label>
                            @php
                                $currentUnit = old('unit_name', $asset->unit_name ?? '');
                                $isUnitInDB = $units->contains(function($u) use ($currentUnit) {
                                    return strcasecmp(trim($u->name), trim($currentUnit)) === 0;
                                });
                            @endphp
                            
                            <select name="unit_name" required class="w-full rounded-md border-gray-300 py-2.5 px-3 focus:ring-[#004A54]">
                                <option value="">— Pilih Unit —</option>
                                @if(!empty($currentUnit) && !$isUnitInDB)
                                    <option value="{{ $currentUnit }}" selected class="bg-red-50 text-red-600 font-semibold">
                                        {{ $currentUnit }} (Belum terdaftar di Master Unit)
                                    </option>
                                @endif
                                @foreach($units as $unit)
                                    <option value="{{ $unit->name }}" {{ strcasecmp(trim($currentUnit), trim($unit->name)) === 0 ? 'selected' : '' }}>
                                        {{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Tanggal Pemeriksaan Terakhir</label>
                            <input type="date" name="last_maintenance_date" value="{{ old('last_maintenance_date', $asset->last_maintenance_date ?? '') }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 py-2.5 px-3 focus:ring-[#004A54]">
                        </div>
                    </div>

                    {{-- BARIS 4: 3 Kolom (Dengan Textarea dan Input Bertumpuk) --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Deskripsi / Peran Aset</label>
                            <textarea name="description" rows="4" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 py-2.5 px-3 focus:ring-[#004A54]">{{ old('description', $asset->description ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Keterangan Lokasi Aset</label>
                            <textarea name="location_desc" rows="4" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 py-2.5 px-3 focus:ring-[#004A54]">{{ old('location_desc', $asset->location_desc ?? '') }}</textarea>
                        </div>
                        
                        {{-- PIC Pencatat & Bidang (Bertumpuk di 1 Kolom) --}}
                        <div class="flex flex-col justify-between space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">PIC Pencatat <span class="text-red-500">*</span></label>
                                <input type="text" name="pic" value="{{ old('pic', $asset->pic ?? auth()->user()->name) }}" readonly class="w-full rounded-md border-gray-300 bg-gray-100 dark:bg-gray-600 py-2.5 px-3 text-gray-500 cursor-not-allowed">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Bidang Pencatat Aset</label>
                                <input type="text" name="pic_department" value="{{ old('pic_department', $asset->pic_department ?? auth()->user()->department ?? '') }}" readonly class="w-full rounded-md border-gray-300 bg-gray-100 dark:bg-gray-600 py-2.5 px-3 text-gray-500 cursor-not-allowed">
                            </div>
                        </div>
                    </div>

                </div>
                
                {{-- Garis Pemisah dengan Spesifik --}}
                <hr class="mt-10 border-gray-200 dark:border-gray-700">
            </div>

            {{-- ===================== ATRIBUT SPESIFIK (DINAMIS) ===================== --}}
            <div class="space-y-10">
                @php
                    // MENGAMBIL FIELD SELAIN ATRIBUT UMUM AGAR TIDAK DOUBLE
                    $specificFields = $currentCategory->fields->where('group_name', '!=', 'ATRIBUT UMUM');
                    $groupedFields = $specificFields->groupBy('group_name');
                @endphp

                @foreach($groupedFields as $groupName => $fields)
                    <div>
                        {{-- Judul Grup (Jika kosong, tampilkan "Atribut Spesifik") --}}
                        <h3 class="text-xs font-bold text-[#004A54] uppercase tracking-widest mb-6">
                            {{ empty($groupName) ? 'Atribut Spesifik' : $groupName }}
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-6">
                            @foreach($fields as $field)
                                @php
                                    $val = old($field->field_key, $asset->specifications[$field->field_key] ?? '');
                                @endphp
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                        {{ $field->name }} 
                                        @if($field->is_required) <span class="text-red-500">*</span> @endif
                                    </label>
                                    
                                    @if($field->field_type === 'select')
                                        <select name="{{ $field->field_key }}" {{ $field->is_required ? 'required' : '' }} 
                                                class="w-full rounded-md border-gray-300 py-2.5 px-3 focus:ring-[#004A54] focus:border-[#004A54] text-gray-700">
                                            <option value="">— Pilih {{ $field->name }} —</option>
                                            
                                            @php 
                                                // PERBAIKAN BUG MALFORMED COMPILER DI SINI:
                                                // Mencegah error null atau array yang salah struktur.
                                                $optionsList = $field->options;
                                                if (is_string($optionsList)) {
                                                    $optionsList = explode(',', $optionsList);
                                                }
                                                if (!is_array($optionsList)) {
                                                    $optionsList = [];
                                                }
                                            @endphp
                                            
                                            @foreach($optionsList as $opt)
                                                @php $opt = trim($opt); @endphp
                                                <option value="{{ $opt }}" {{ $val == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                            @endforeach
                                        </select>
                                    
                                    @elseif($field->field_type === 'textarea')
                                        <textarea name="{{ $field->field_key }}" rows="2" {{ $field->is_required ? 'required' : '' }} 
                                                  class="w-full rounded-md border-gray-300 py-2.5 px-3 focus:ring-[#004A54] focus:border-[#004A54] text-gray-700" 
                                                  placeholder="Contoh: Ketik {{ strtolower($field->name) }} di sini...">{{ $val }}</textarea>
                                    
                                    @else
                                        <input type="{{ $field->field_type }}" name="{{ $field->field_key }}" value="{{ $val }}" 
                                               {{ $field->is_required ? 'required' : '' }} 
                                               placeholder="{{ $field->field_type === 'date' ? 'mm/dd/yyyy' : 'Contoh: ' . $field->name }}"
                                               onkeyup="if(this.name === 'id_aset') document.getElementById('hidden_name').value = this.value;"
                                               class="w-full rounded-md border-gray-300 py-2.5 px-3 focus:ring-[#004A54] focus:border-[#004A54] text-gray-700">
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ===================== TOMBOL AKSI ===================== --}}
            <div class="pt-8 border-t border-gray-100 flex justify-end gap-3 mt-10">
                <a href="{{ route('manage-asset.generic.index', $currentCategory->slug) }}" class="px-5 py-2.5 border border-gray-300 rounded-md text-sm text-gray-700 font-semibold hover:bg-gray-50 transition-colors shadow-sm">
                    Batal
                </a>
                <button type="submit" class="bg-[#004A54] text-white px-5 py-2.5 rounded-md text-sm font-semibold hover:bg-[#00363d] transition-colors shadow-sm">
                    Simpan Aset
                </button>
            </div>
            
        </form>
    </div>
</main>
@endsection