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

            <h1 class="text-2xl font-bold text-pln-800 dark:text-white">{{ $unit ? 'Edit Unit' : 'Tambah Unit Baru' }}</h1>

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
                        <h2 class="text-base font-bold text-pln-800 dark:text-white">Informasi Unit</h2>
                        <p class="text-sm text-gray-400 dark:text-gray-500">Lengkapi detail unit di bawah ini.</p>
                    </div>
                </div>

                <div class="border-t border-gray-100 dark:border-gray-700 p-8 space-y-6">

                    @if ($unit)
                        {{-- Mode edit: form disederhanakan, cuma Nama, Functloc, Latitude, Longitude --}}
                        <input type="hidden" name="level" value="{{ $unit->level }}">
                        <input type="hidden" name="parent_id" value="{{ $unit->parent_id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Nama</label>
                                <input type="text" name="name" value="{{ old('name', $unit->name) }}" required
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Functloc</label>
                                <input type="text" name="code" value="{{ old('code', $unit->code) }}" placeholder="Contoh: TRS-3512-254.254"
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Latitude</label>
                                <input type="text" name="latitude" value="{{ old('latitude', $unit->latitude) }}" placeholder="-6.914744"
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Longitude</label>
                                <input type="text" name="longitude" value="{{ old('longitude', $unit->longitude) }}" placeholder="107.609810"
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                            </div>
                        </div>
                    @else
                        {{-- Mode tambah: form lengkap, pilih UPT + auto-detect UIT --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Level</label>
                                <input type="hidden" name="level" value="4">
                                <div class="mt-1.5 w-full rounded-md border border-gray-200 dark:border-gray-600 bg-gray-100 dark:bg-gray-700/50 py-2.5 px-3 text-sm text-gray-500 dark:text-gray-400">
                                    Level 4 — GI
                                </div>
                                <p class="mt-1 text-[11px] text-gray-400 dark:text-gray-500">Form ini khusus input GI. Level 2 (UPT) & 3 (ULTG) otomatis terdeteksi dari ULTG yang dipilih.</p>
                            </div>

                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Functloc (opsional)</label>
                                <input type="text" name="code" value="{{ old('code') }}" placeholder="Contoh: TRS-3512-254.254"
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Nama Unit (GI)</label>
                                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: GI Bandung Selatan"
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                            </div>

                            <div id="parentWrapper">
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Induk Unit (UPT)</label>
                                <select name="parent_id" id="parentSelect" required
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                                    <option value="">— Pilih UPT —</option>
                                    @foreach ($parents as $p)
                                        @continue($p->level != 2)
                                        <option value="{{ $p->id }}"
                                            data-uit="{{ $p->parent->name ?? '—' }}"
                                            @selected(old('parent_id') == $p->id)>
                                            {{ $p->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-[11px] text-gray-400 dark:text-gray-500">ULTG di bawahnya tidak perlu dipilih — GI ini otomatis diparent-kan langsung ke UPT.</p>
                            </div>
                        </div>

                        <div id="hierarchyPreview" class="hidden flex items-start gap-3 rounded-lg bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 px-4 py-3">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                            <p class="text-xs text-gray-600 dark:text-gray-300">
                                Terdeteksi otomatis: <span id="hierarchyUit" class="font-semibold"></span>
                                → <span id="hierarchyUpt" class="font-semibold"></span>
                                → <span class="font-semibold">GI ini</span>
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Latitude</label>
                                <input type="text" name="latitude" value="{{ old('latitude') }}" placeholder="-6.914744"
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Longitude</label>
                                <input type="text" name="longitude" value="{{ old('longitude') }}" placeholder="107.609810"
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                            </div>
                        </div>
                    @endif

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
    // Sidebar admin submenu toggle
    const adminMenuToggle = document.getElementById('adminMenuToggle');
    const adminSubmenu = document.getElementById('adminSubmenu');
    const adminMenuChevron = document.getElementById('adminMenuChevron');

    if (adminMenuToggle && adminSubmenu) {
        adminMenuToggle.addEventListener('click', () => {
            adminSubmenu.classList.toggle('hidden');
            adminMenuChevron.classList.toggle('rotate-180');
        });
    }

    // Deteksi otomatis UIT dari UPT yang dipilih (cuma ada di mode tambah/create)
    const parentSelect = document.getElementById('parentSelect');
    const hierarchyPreview = document.getElementById('hierarchyPreview');
    const hierarchyUpt = document.getElementById('hierarchyUpt');
    const hierarchyUit = document.getElementById('hierarchyUit');

    if (parentSelect && hierarchyPreview) {
        function updateHierarchyPreview() {
            const selected = parentSelect.options[parentSelect.selectedIndex];
            if (!selected || !selected.value) {
                hierarchyPreview.classList.add('hidden');
                return;
            }
            hierarchyUpt.textContent = selected.textContent.trim();
            hierarchyUit.textContent = selected.dataset.uit || '—';
            hierarchyPreview.classList.remove('hidden');
        }
        parentSelect.addEventListener('change', updateHierarchyPreview);
        updateHierarchyPreview();
    }
</script>
@endsection