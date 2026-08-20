@extends('layouts.app', ['title' => 'Atur Spesifikasi: ' . $category->name])

@section('content')
<main class="p-6 space-y-6 w-full max-w-full">
    
    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('manage-category') }}" class="p-2 bg-white dark:bg-gray-800 rounded-full shadow-sm hover:bg-gray-50 border border-gray-200 transition-colors">
            <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Rancang Form: {{ $category->name }}</h1>
            <p class="text-sm text-gray-500">Tentukan atribut atau kolom apa saja yang perlu diisi saat menambahkan aset {{ $category->name }}.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-100 text-green-700 rounded-lg font-medium">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-100 text-red-700 rounded-lg font-medium">{{ session('error') }}</div>
    @endif

    {{-- KONTEN UTAMA: Grid dengan self-start di kolom kiri agar tidak membuat Phantom Scroll --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- KOLOM KIRI: FORM TAMBAH / EDIT FIELD --}}
        <div class="lg:col-span-1 self-start sticky top-6">
            <form id="fieldForm" action="{{ route('manage-category.fields.store', $category->id) }}" method="POST" class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 shadow-sm space-y-4">
                @csrf
                <div id="methodContainer"></div>

                <div>
                    <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-300">Nama Kolom (Label)</label>
                    <input type="text" name="name" id="inputName" required placeholder="Cth: IP Address, Plat Nomor..." class="w-full rounded-md border-gray-300 py-2.5 px-3 focus:ring-[#004A54] focus:border-[#004A54]">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-300">Tipe Inputan</label>
                    <select name="field_type" id="inputType" required class="w-full rounded-md border-gray-300 py-2.5 px-3 focus:ring-[#004A54] focus:border-[#004A54]" onchange="document.getElementById('optionsWrapper').style.display = this.value === 'select' ? 'block' : 'none'">
                        <option value="text">Teks Singkat</option>
                        <option value="number">Angka</option>
                        <option value="date">Tanggal</option>
                        <option value="select">Dropdown (Pilihan)</option>
                    </select>
                </div>

                <div id="optionsWrapper" style="display: none;">
                    <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-300">Daftar Pilihan</label>
                    <input type="text" name="options" id="inputOptions" placeholder="Cth: Aktif, Rusak, Perbaikan" class="w-full rounded-md border-gray-300 py-2.5 px-3 focus:ring-[#004A54] focus:border-[#004A54]">
                    <p class="text-[11px] text-gray-500 mt-1">Pisahkan tiap pilihan dengan tanda koma (,)</p>
                </div>

                <div class="flex items-center gap-6 pt-1">
                    <label class="flex items-center gap-2 text-sm cursor-pointer text-gray-700 dark:text-gray-300 font-medium">
                        <input type="checkbox" name="is_required" id="inputRequired" class="rounded text-[#004A54] focus:ring-[#004A54] w-4 h-4"> Wajib Diisi
                    </label>
                    <label class="flex items-center gap-2 text-sm cursor-pointer text-gray-700 dark:text-gray-300 font-medium" title="Mengatur apakah kolom ini otomatis dicentang saat membuat atribut baru">
                        <input type="checkbox" name="show_in_table" id="inputShow" checked class="rounded text-[#004A54] focus:ring-[#004A54] w-4 h-4"> Tampil di Tabel
                    </label>
                </div>

                <div class="flex flex-col gap-2 pt-4">
                    <button type="submit" id="submitBtn" class="w-full bg-[#004A54] text-white px-4 py-2.5 rounded-md font-semibold hover:bg-[#00363d] transition-colors shadow-sm">
                        + Tambah Kolom
                    </button>
                    <button type="button" id="cancelBtn" onclick="cancelEdit()" style="display: none;" class="w-full bg-gray-100 text-gray-700 border border-gray-300 px-4 py-2.5 rounded-md font-semibold hover:bg-gray-200 transition-colors">
                        Batal Edit
                    </button>
                </div>
            </form>
        </div>

        {{-- KOLOM KANAN: PREVIEW DAFTAR FIELD --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 dark:bg-gray-900/50">
                <h3 class="font-bold text-gray-700 dark:text-gray-200">Daftar Spesifikasi {{ $category->name }}</h3>
            </div>
            
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-4 font-bold text-gray-600 dark:text-gray-300">Nama Kolom</th>
                            <th class="px-6 py-4 font-bold text-gray-600 dark:text-gray-300">Tipe</th>
                            <th class="px-6 py-4 font-bold text-gray-600 dark:text-gray-300 text-center">Atribut</th>
                            <th class="px-6 py-4 font-bold text-gray-600 dark:text-gray-300 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        
                        {{-- ===================== 1. ATRIBUT UMUM (BAWAAN SISTEM) ===================== --}}
                        <tr class="bg-gray-50/80 dark:bg-gray-800/80">
                            <td colspan="4" class="px-6 py-3 text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest bg-gray-50 dark:bg-gray-900/30">
                                Atribut Umum (Bawaan Sistem)
                            </td>
                        </tr>

                        @php
                            $defaultFields = [
                                ['name' => 'ID Aset', 'type' => 'Teks Singkat', 'required' => true],
                                ['name' => 'Tanggal Mulai Aktif / Perolehan', 'type' => 'Tanggal', 'required' => false],
                                ['name' => 'Status Kepemilikan', 'type' => 'Pilihan (Dropdown)', 'required' => false],
                                ['name' => 'Ket. Status Kepemilikan', 'type' => 'Teks Singkat', 'required' => false],
                                ['name' => 'Status Kondisi Aset', 'type' => 'Pilihan (Dropdown)', 'required' => true],
                                ['name' => 'Status Operasional', 'type' => 'Pilihan (Dropdown)', 'required' => true],
                                ['name' => 'Tingkat Kritikalitas', 'type' => 'Pilihan (Dropdown)', 'required' => true],
                                ['name' => 'Klasifikasi Keamanan', 'type' => 'Pilihan (Dropdown)', 'required' => false],
                                ['name' => 'Lokasi Aset Saat Ini (Kode)', 'type' => 'Pilihan (Dropdown)', 'required' => true],
                                ['name' => 'Tanggal Pemeriksaan Terakhir', 'type' => 'Tanggal', 'required' => false],
                                ['name' => 'Deskripsi / Peran Aset', 'type' => 'Teks Panjang', 'required' => false],
                                ['name' => 'Keterangan Lokasi Aset', 'type' => 'Teks Panjang', 'required' => false],
                                ['name' => 'PIC Pencatat', 'type' => 'Teks Singkat', 'required' => true],
                                ['name' => 'Bidang Pencatat Aset', 'type' => 'Teks Singkat', 'required' => false],
                            ];
                        @endphp

                        @foreach($defaultFields as $df)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                            <td class="px-6 py-3.5 font-medium text-gray-700 dark:text-gray-300">{{ $df['name'] }}</td>
                            <td class="px-6 py-3.5 text-sm text-gray-500 dark:text-gray-400">{{ $df['type'] }}</td>
                            <td class="px-6 py-3.5 text-center">
                                <div class="flex items-center justify-center gap-4">
                                    {{-- Label Wajib / Opsional --}}
                                    @if($df['required'])
                                        <span class="inline-flex items-center justify-center px-2 py-0.5 rounded text-[11px] font-medium bg-red-100 text-red-700 min-w-[60px]">Wajib</span>
                                    @else
                                        <span class="inline-flex items-center justify-center px-2 py-0.5 rounded text-[11px] font-medium bg-gray-100 text-gray-600 min-w-[60px]">Opsional</span>
                                    @endif
                                    
                                    {{-- TOGGLE VISUAL UNTUK ATRIBUT UMUM (menggunakan class 'hidden' bukan 'sr-only' untuk mencegah scroll bug) --}}
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="hidden peer" onchange="alert('Untuk mengaktifkan fitur sembunyikan Atribut Bawaan dari tabel, diperlukan penambahan kolom konfigurasi di sistem Database. Saat ini berstatus permanen.'); this.checked = true;" checked>
                                        <div class="relative w-9 h-5 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#004A54]"></div>
                                        <span class="ms-2 text-[11px] font-medium text-gray-600">Tampil di Tabel</span>
                                    </label>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- TOMBOL EDIT UMUM (Visual Only) --}}
                                    <button type="button" onclick="alert('Atribut Bawaan Sistem tidak dapat diedit manual untuk menjaga kestabilan fitur inti.')"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 hover:bg-gray-100 transition-colors" title="Edit (Terkunci)">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                        </svg>
                                    </button>

                                    {{-- TOMBOL HAPUS UMUM (Terkunci) --}}
                                    <button type="button" disabled class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-100 bg-gray-50 text-gray-300 cursor-not-allowed" title="Atribut Permanen">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                        {{-- ===================== 2. ATRIBUT SPESIFIKASI TAMBAHAN ===================== --}}
                        <tr class="bg-cyan-50/50 dark:bg-gray-800/80">
                            <td colspan="4" class="px-6 py-3 text-[11px] font-bold text-[#004A54] dark:text-accent-400 uppercase tracking-widest bg-cyan-50/50 dark:bg-gray-800 border-t border-gray-200">
                                Spesifikasi Tambahan (Dinamis)
                            </td>
                        </tr>

                        @forelse(\App\Models\AssetCategoryField::where('asset_category_id', $category->id)->orderBy('id')->get() as $field)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                            <td class="px-6 py-3.5 font-medium text-gray-800 dark:text-white">{{ $field->name }}</td>
                            <td class="px-6 py-3.5 text-sm text-gray-500 dark:text-gray-400">{{ $field->field_type }}</td>
                            <td class="px-6 py-3.5 text-center">
                                <div class="flex items-center justify-center gap-4">
                                    {{-- Status Wajib Diisi --}}
                                    @if($field->is_required)
                                        <span class="inline-flex items-center justify-center px-2 py-0.5 rounded text-[11px] font-medium bg-red-100 text-red-700 min-w-[60px]">Wajib</span>
                                    @else
                                        <span class="inline-flex items-center justify-center px-2 py-0.5 rounded text-[11px] font-medium bg-gray-100 text-gray-600 min-w-[60px]">Opsional</span>
                                    @endif
                                    
                                    {{-- TOGGLE SWITCH TAMPIL DI TABEL (REAL-TIME AJAX) --}}
                                    <label class="inline-flex items-center cursor-pointer">
                                        {{-- Menggunakan class 'hidden' alih-alih 'sr-only' untuk mencegah bug overflow --}}
                                        <input type="checkbox" class="hidden peer" onchange="toggleTableVisibility({{ $field->id }}, this)" {{ $field->show_in_table ? 'checked' : '' }}>
                                        <div class="relative w-9 h-5 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#004A54]"></div>
                                        <span class="ms-2 text-[11px] font-medium text-gray-600 dark:text-gray-300">Tampil di Tabel</span>
                                    </label>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    
                                    {{-- TOMBOL EDIT --}}
                                    @php
                                        $optionsStr = is_array($field->options) ? implode(',', $field->options) : $field->options;
                                    @endphp
                                    <button type="button" 
                                        onclick="editField({{ $field->id }}, '{{ addslashes($field->name) }}', '{{ $field->field_type }}', '{{ addslashes($optionsStr) }}', {{ $field->is_required ? 1 : 0 }}, {{ $field->show_in_table ? 1 : 0 }})" 
                                        class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-300 text-gray-500 hover:border-blue-500 hover:text-blue-600 hover:bg-blue-50 transition-all focus:outline-none" title="Edit Detail">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                        </svg>
                                    </button>

                                    {{-- TOMBOL HAPUS --}}
                                    <form action="{{ route('manage-category.fields.destroy', $field->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus spesifikasi dinamis ini secara permanen?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-300 text-gray-500 hover:border-red-500 hover:text-red-600 hover:bg-red-50 transition-all focus:outline-none" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-400 text-sm">
                                <svg class="w-8 h-8 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                                Belum ada atribut spesifikasi tambahan.<br>
                                <span class="text-xs">Gunakan form di sebelah kiri untuk menambahkan.</span>
                            </td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

{{-- ======================================================== --}}
{{-- SCRIPT UNTUK FITUR EDIT & TOGGLE REAL-TIME TANPA LOADING --}}
{{-- ======================================================== --}}
<script>
    function toggleTableVisibility(fieldId, checkboxElement) {
        fetch(`/manage-category/fields/${fieldId}/toggle-table`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(!data.success) {
                alert('Gagal menyimpan perubahan. Silakan refresh halaman.');
                checkboxElement.checked = !checkboxElement.checked; 
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan jaringan.');
            checkboxElement.checked = !checkboxElement.checked; 
        });
    }

    function editField(id, name, type, options, isRequired, showInTable) {
        const form = document.getElementById('fieldForm');
        form.action = `/manage-category/fields/${id}/update`;
        document.getElementById('methodContainer').innerHTML = '<input type="hidden" name="_method" value="PATCH">';
        
        document.getElementById('inputName').value = name;
        document.getElementById('inputType').value = type;
        
        const optWrapper = document.getElementById('optionsWrapper');
        if(type === 'select') {
            optWrapper.style.display = 'block';
            document.getElementById('inputOptions').value = options;
        } else {
            optWrapper.style.display = 'none';
            document.getElementById('inputOptions').value = '';
        }

        document.getElementById('inputRequired').checked = (isRequired === 1);
        document.getElementById('inputShow').checked = (showInTable === 1);

        const submitBtn = document.getElementById('submitBtn');
        submitBtn.innerHTML = 'Simpan Perubahan';
        submitBtn.classList.remove('bg-[#004A54]', 'hover:bg-[#00363d]');
        submitBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
        
        document.getElementById('cancelBtn').style.display = 'block';
        window.scrollTo({ top: 0, behavior: 'smooth' }); 
    }

    function cancelEdit() {
        const form = document.getElementById('fieldForm');
        form.action = "{{ route('manage-category.fields.store', $category->id) }}";
        document.getElementById('methodContainer').innerHTML = '';
        
        form.reset();
        document.getElementById('optionsWrapper').style.display = 'none';
        
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.innerHTML = '+ Tambah Kolom';
        submitBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
        submitBtn.classList.add('bg-[#004A54]', 'hover:bg-[#00363d]');
        
        document.getElementById('cancelBtn').style.display = 'none';
    }
</script>
@endsection