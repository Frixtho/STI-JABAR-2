@extends('layouts.app', ['title' => ($unit ? 'Edit Unit' : 'Tambah Unit') . ' — PLN Financial'])

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
                    class="w-full rounded-md border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 py-2 pl-10 pr-3 text-sm text-gray-700 dark:text-gray-200 placeholder-gray-400 focus:border-[#004A54] focus:bg-white dark:focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-[#004A54]/20">
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
                <a href="{{ route('manage-unit') }}" class="text-gray-400 dark:text-gray-500 hover:text-[#004A54] dark:hover:text-accent-400 transition-colors">Manage Unit</a>
                <svg class="w-3.5 h-3.5 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
                <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $unit ? 'Edit Unit' : 'Tambah Unit' }}</span>
            </nav>

            <h1 class="text-2xl font-bold text-[#004A54] dark:text-white">{{ $unit ? 'Edit Unit' : 'Tambah Unit Baru' }}</h1>

            @if ($errors->any())
                <div class="rounded-md border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/30 px-4 py-3 text-sm text-red-700 dark:text-red-400">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ $unit ? route('manage-unit.update', $unit) : route('manage-unit.store') }}"
                class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                @csrf
                @if ($unit) @method('PATCH') @endif

                {{-- Card Header --}}
                <div class="flex items-start gap-4 p-8 pb-6">
                    <div class="w-11 h-11 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-[#004A54] dark:text-white">Informasi Unit</h2>
                        <p class="text-sm text-gray-400 dark:text-gray-500">Lengkapi detail hierarki unit di bawah ini.</p>
                    </div>
                </div>

                {{-- FORM HIERARKI CERDAS --}}
                <div class="border-t border-gray-100 dark:border-gray-700 p-8 space-y-6">

                    @php
                        // Persiapan Data Induk (Mendukung Create maupun Edit)
                        $allParents = $parents ?? $parentUnits ?? \App\Models\Unit::all();
                        
                        $selectedLvl1 = old('parent_lvl_1');
                        $selectedLvl2 = old('parent_lvl_2');
                        $selectedLvl3 = old('parent_lvl_3');

                        // Jika sedang Edit data, otomatis cari rantai parent-nya ke atas
                        if (isset($unit) && $unit && $unit->parent_id) {
                            $currId = $unit->parent_id;
                            while ($currId) {
                                $p = $allParents->firstWhere('id', $currId);
                                if (!$p) break;
                                
                                if ($p->level == 3) $selectedLvl3 = $selectedLvl3 ?: $p->id;
                                if ($p->level == 2) $selectedLvl2 = $selectedLvl2 ?: $p->id;
                                if ($p->level == 1) $selectedLvl1 = $selectedLvl1 ?: $p->id;
                                
                                $currId = $p->parent_id;
                            }
                        }
                    @endphp

                    {{-- BARIS 1: Level & Functloc --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Level Unit <span class="text-red-500">*</span></label>
                            <select name="level" id="levelSelect" required onchange="toggleParentFields()"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                                <option value="">— Pilih Level Unit —</option>
                                <option value="1" @selected(old('level', $unit->level ?? '') == 1)>Level 1 (UID / UIT / UIP)</option>
                                <option value="2" @selected(old('level', $unit->level ?? '') == 2)>Level 2 (UP3 / UPT)</option>
                                <option value="3" @selected(old('level', $unit->level ?? '') == 3)>Level 3 (ULP / ULTG)</option>
                                <option value="4" @selected(old('level', $unit->level ?? '') == 4)>Level 4 (GI / KP)</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Functloc (opsional)</label>
                            <input type="text" name="code" value="{{ old('code', $unit->code ?? '') }}" placeholder="Contoh: TRS-3512-254.254"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                        </div>
                    </div>

                    {{-- BARIS 2: Nama Unit --}}
                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Nama Unit <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $unit->name ?? '') }}" required placeholder="Contoh: GI Bandung Selatan"
                            class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                    </div>

                    {{-- BARIS 3: DROPDOWN BERUNTUN (CASCADING) --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t border-gray-100 dark:border-gray-700 hidden" id="parentsWrapper">
                        
                        <div id="wrapper_lvl_1" class="hidden">
                            <label class="text-xs font-bold text-[#004A54] dark:text-accent-400">1. Induk Level 1 (UID/UIT)</label>
                            <select name="parent_lvl_1" id="select_lvl_1" onchange="filterDropdowns(1)"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                                <option value="">— Pilih Level 1 —</option>
                                @foreach ($allParents as $p)
                                    @continue($p->level != 1)
                                    <option value="{{ $p->id }}" @selected($selectedLvl1 == $p->id)>{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="wrapper_lvl_2" class="hidden">
                            <label class="text-xs font-bold text-[#004A54] dark:text-accent-400">2. Induk Level 2 (UP3/UPT)</label>
                            <select name="parent_lvl_2" id="select_lvl_2" onchange="filterDropdowns(2)"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                                <option value="">— Pilih Level 2 —</option>
                                @foreach ($allParents as $p)
                                    @continue($p->level != 2)
                                    <option value="{{ $p->id }}" data-parent="{{ $p->parent_id }}" @selected($selectedLvl2 == $p->id)>{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="wrapper_lvl_3" class="hidden">
                            <label class="text-xs font-bold text-[#004A54] dark:text-accent-400">3. Induk Level 3 (ULP/ULTG)</label>
                            <select name="parent_lvl_3" id="select_lvl_3"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                                <option value="">— Pilih Level 3 —</option>
                                @foreach ($allParents as $p)
                                    @continue($p->level != 3)
                                    <option value="{{ $p->id }}" data-parent="{{ $p->parent_id }}" @selected($selectedLvl3 == $p->id)>{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    {{-- BARIS 4: KORDINAT --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Latitude</label>
                            <input type="text" name="latitude" value="{{ old('latitude', $unit->latitude ?? '') }}" 
                                placeholder="-6.914744" inputmode="decimal"
                                oninput="this.value = this.value.replace(/[^0-9.,-]/g, '');"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Longitude</label>
                            <input type="text" name="longitude" value="{{ old('longitude', $unit->longitude ?? '') }}" 
                                placeholder="107.609810" inputmode="decimal"
                                oninput="this.value = this.value.replace(/[^0-9.,-]/g, '');"
                                class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                        </div>
                    </div>

                    {{-- Info box --}}
                    <div class="flex items-start gap-3 rounded-lg bg-cyan-50 dark:bg-cyan-900/20 border border-cyan-100 dark:border-cyan-900/40 px-4 py-3">
                        <svg class="w-4 h-4 text-cyan-600 dark:text-cyan-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <circle cx="12" cy="12" r="9" stroke-linecap="round" stroke-linejoin="round" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8h.01M11 12h1v4h1" />
                        </svg>
                        <p class="text-xs text-cyan-800 dark:text-cyan-300">Koordinat wajib diisi kalau unit ini mau dipakai untuk hitung jarak KMS (biasanya diisi untuk GI dan UPT).</p>
                    </div>
                </div>

                <div class="flex justify-end gap-3 px-8 py-5 border-t border-gray-100 dark:border-gray-700">
                    <a href="{{ route('manage-unit') }}" class="border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-6 py-2.5 rounded-md text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Batal</a>
                    <button type="submit" class="inline-flex items-center gap-2 bg-[#004A54] text-white px-6 py-2.5 rounded-md text-sm font-medium hover:bg-[#00363d] transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5v9.75a.75.75 0 0 0 .75.75h6a.75.75 0 0 0 .75-.75V4.5m-9 0h9a1.5 1.5 0 0 1 1.5 1.5v13.5a1.5 1.5 0 0 1-1.5 1.5h-13.5a1.5 1.5 0 0 1-1.5-1.5V7.629c0-.398.158-.78.44-1.06l2.129-2.13c.281-.281.663-.44 1.06-.44Z" />
                        </svg>
                        {{ $unit ? 'Simpan Perubahan' : 'Simpan Unit' }}
                    </button>
                </div>
            </form>
        </main>
    </div>
</div>

<script>
    // FUNGSI 1: MUNCUL/SEMBUNYIKAN DROPDOWN BERDASARKAN LEVEL
    function toggleParentFields() {
        const level = parseInt(document.getElementById('levelSelect').value) || 0;
        
        const mainWrapper = document.getElementById('parentsWrapper');
        const w1 = document.getElementById('wrapper_lvl_1'); const s1 = document.getElementById('select_lvl_1');
        const w2 = document.getElementById('wrapper_lvl_2'); const s2 = document.getElementById('select_lvl_2');
        const w3 = document.getElementById('wrapper_lvl_3'); const s3 = document.getElementById('select_lvl_3');

        // Sembunyikan & Matikan required sementara
        mainWrapper.classList.add('hidden');
        [w1, w2, w3].forEach(w => w.classList.add('hidden'));
        [s1, s2, s3].forEach(s => s.required = false);

        // Aturan Sketsa: Level 1 (Tidak ada induk), Level 2 (Pilih Lvl 1), dst.
        if (level >= 2) { 
            mainWrapper.classList.remove('hidden');
            w1.classList.remove('hidden'); s1.required = true; 
        }
        if (level >= 3) { w2.classList.remove('hidden'); s2.required = true; }
        if (level >= 4) { w3.classList.remove('hidden'); s3.required = true; }
    }

    // FUNGSI 2: FILTER DROPDOWN ANAK BERDASARKAN INDUKNYA (CASCADING)
    function filterDropdowns(sourceLevel) {
        if (sourceLevel === 1) {
            const lvl1Id = document.getElementById('select_lvl_1').value;
            const selectLvl2 = document.getElementById('select_lvl_2');
            
            // Sembunyikan/munculkan opsi di level 2 berdasarkan parent-nya
            Array.from(selectLvl2.options).forEach(opt => {
                if (opt.value === "") return;
                opt.hidden = (opt.getAttribute('data-parent') !== lvl1Id && lvl1Id !== "");
            });
            
            // Jika tidak sedang mengedit, reset pilihan anak saat parent diganti
            if(!window.isEditingMode) { selectLvl2.value = ""; }
            filterDropdowns(2); // Panggil filter anak di bawahnya lagi
        }
        
        if (sourceLevel === 2) {
            const lvl2Id = document.getElementById('select_lvl_2').value;
            const selectLvl3 = document.getElementById('select_lvl_3');
            
            Array.from(selectLvl3.options).forEach(opt => {
                if (opt.value === "") return;
                opt.hidden = (opt.getAttribute('data-parent') !== lvl2Id && lvl2Id !== "");
            });
            
            if(!window.isEditingMode) { selectLvl3.value = ""; }
        }
    }

    // Eksekusi saat halaman pertama kali dimuat
    document.addEventListener('DOMContentLoaded', () => {
        window.isEditingMode = {{ $unit ? 'true' : 'false' }};
        toggleParentFields();
        filterDropdowns(1);
        window.isEditingMode = false; // Matikan flag setelah load agar interaksi berikutnya normal
    });
</script>
@endsection