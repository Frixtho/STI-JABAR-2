@extends('layouts.app', ['title' => 'Import Unit — PLN Financial'])

@section('content')
<div class="min-h-screen flex bg-gray-50 dark:bg-gray-900">

    {{-- ===================== SIDEBAR ===================== --}}
    <aside class="hidden lg:flex lg:flex-col lg:w-60 bg-pln-800 text-white shrink-0">
        <div class="px-6 py-6">
            <p class="font-bold text-lg leading-tight">PLN Financial</p>
            <p class="text-[10px] tracking-[0.2em] text-accent-400">UTILITY MANAGEMENT</p>
        </div>

        <nav class="flex-1 px-4 space-y-1 mt-2">
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-white/10 text-white' : 'text-pln-100 hover:bg-white/5' }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                </svg>
                Dashboard
            </a>

            @php
                $adminActive = request()->routeIs('manage-user*') || request()->routeIs('manage-unit*') || request()->routeIs('manage-asset*');
            @endphp

            <div>
                <button type="button" id="adminMenuToggle"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-md text-sm font-medium {{ $adminActive ? 'text-white bg-white/5' : 'text-pln-100 hover:bg-white/5' }}">
                    <span class="flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0" />
                        </svg>
                        Admin
                    </span>
                    <svg id="adminMenuChevron" class="w-3.5 h-3.5 transition-transform duration-200 {{ $adminActive ? 'rotate-180' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>
                <div id="adminSubmenu" class="{{ $adminActive ? '' : 'hidden' }} ml-7 mt-1 border-l border-white/10 pl-3 space-y-1">
                    <a href="{{ route('manage-user') }}"
                       class="relative block px-2 py-2 text-sm {{ request()->routeIs('manage-user*') ? 'text-white font-medium before:absolute before:-left-[13px] before:top-1/2 before:-translate-y-1/2 before:w-1 before:h-5 before:bg-accent-400 before:rounded-r' : 'text-pln-100 hover:text-white' }}">
                        Manage User
                    </a>
                    <a href="{{ route('manage-unit') }}"
                       class="relative block px-2 py-2 text-sm {{ request()->routeIs('manage-unit*') ? 'text-white font-medium before:absolute before:-left-[13px] before:top-1/2 before:-translate-y-1/2 before:w-1 before:h-5 before:bg-accent-400 before:rounded-r' : 'text-pln-100 hover:text-white' }}">
                        Manage Unit
                    </a>
                    <a href="{{ route('manage-asset') }}"
                       class="relative block px-2 py-2 text-sm {{ request()->routeIs('manage-asset*') ? 'text-white font-medium before:absolute before:-left-[13px] before:top-1/2 before:-translate-y-1/2 before:w-1 before:h-5 before:bg-accent-400 before:rounded-r' : 'text-pln-100 hover:text-white' }}">
                        Manage Asset
                    </a>
                </div>
            </div>

            <a href="{{ route('settings') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium {{ request()->routeIs('settings*') ? 'bg-white/10 text-white' : 'text-pln-100 hover:bg-white/5' }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 0 1 0 .255c-.007.378.138.751.43.991l1.005.828c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 0 1 0-.255c.007-.378-.138-.751-.43-.991l-1.004-.828a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l-.214-1.281Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
                Settings
            </a>
        </nav>

        <div class="p-4 border-t border-white/10">
            <div class="flex items-center gap-3 px-2 py-2 rounded-md bg-white/5">
                <div class="w-8 h-8 rounded-full bg-accent-400 text-pln-800 font-bold text-xs flex items-center justify-center uppercase shrink-0">
                    {{ implode('', array_map(fn($w) => $w[0] ?? '', array_slice(explode(' ', auth()->user()->name), 0, 2))) }}
                </div>
                <div class="leading-tight min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[11px] text-pln-100/70 truncate">{{ auth()->user()->email }}</p>
                    <span class="inline-flex items-center mt-1 text-[9px] font-bold uppercase tracking-wide px-1.5 py-0.5 rounded
                        {{ strcasecmp(auth()->user()->role, 'Admin') === 0 ? 'bg-accent-400 text-pln-800' : 'bg-white/10 text-pln-100' }}">
                        {{ auth()->user()->role }}
                    </span>
                </div>
            </div>
            <div class="mt-3 space-y-1">
                <a href="#" class="flex items-center gap-2 px-2 py-1.5 text-sm text-pln-100 hover:text-white">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="9" stroke-linecap="round" stroke-linejoin="round" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.5 9a2.5 2.5 0 1 1 3.5 2.29c-.7.32-1 .8-1 1.71M12 17h.01" />
                    </svg>
                    Help
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-2 py-1.5 text-sm text-pln-100 hover:text-white">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>

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
                <span class="font-semibold text-gray-700 dark:text-gray-200">Import Unit</span>
            </nav>

            <h1 class="text-2xl font-bold text-pln-800 dark:text-white">Import Unit dari CSV</h1>

            @if (session('success'))
                <div class="rounded-md border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/30 px-4 py-3 text-sm text-green-700 dark:text-green-400">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="rounded-md border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/30 px-4 py-3 text-sm text-red-700 dark:text-red-400">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('import_skipped_reasons') && count(session('import_skipped_reasons')) > 0)
                <div class="rounded-md border border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-900/20 px-4 py-3 text-xs text-amber-800 dark:text-amber-300">
                    <p class="font-semibold mb-1.5">Detail baris yang dilewati:</p>
                    <ul class="list-disc list-inside space-y-1 max-h-64 overflow-y-auto">
                        @foreach (session('import_skipped_reasons') as $reason)
                            <li>{{ $reason }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-md border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/30 px-4 py-3 text-sm text-red-700 dark:text-red-400">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">

                {{-- Card Header --}}
                <div class="flex items-start gap-4 p-8 pb-6">
                    <div class="w-11 h-11 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-pln-800 dark:text-white">Upload File CSV</h2>
                        <p class="text-sm text-gray-400 dark:text-gray-500">Unggah beberapa data unit secara massal sekaligus menggunakan format CSV.</p>
                    </div>
                </div>

                <div class="border-t border-gray-100 dark:border-gray-700 p-8 space-y-5">

                    <form action="{{ route('manage-unit.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Pilihan Jenis Data -->
                        <div class="mb-4">
                            <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-200">Jenis Data</label>
                            <select name="jenis" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2 text-sm bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-[#004A54] focus:outline-none">
                                <option value="generic">Generic (UIT, UPT, ULTG)</option>
                                <option value="gi" selected>Gardu Induk (GI) & Tower</option>
                            </select>
                        </div>

                        <!-- Upload Multiple File CSV -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-200">File CSV (Bisa pilih lebih dari satu)</label>
                            <input type="file" name="files[]" id="fileInput" accept=".csv,.txt" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2 text-sm bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-[#004A54] file:text-white hover:file:bg-[#003840] cursor-pointer" multiple required>
                            <p class="text-xs text-gray-400 mt-1">Tekan dan tahan tombol Ctrl (Windows) atau Cmd (Mac) di keyboard untuk memilih banyak file sekaligus.</p>
                            
                            {{-- Container untuk menampilkan daftar file yang terpilih secara dinamis --}}
                            <div id="fileListContainer" class="hidden mt-3 p-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg text-xs text-gray-600 dark:text-gray-300">
                                <p class="font-semibold mb-1 text-[#004A54] dark:text-accent-400">File yang dipilih (<span id="fileCount">0</span> file):</p>
                                <ul id="fileNamesList" class="list-disc list-inside space-y-0.5"></ul>
                            </div>
                        </div>

                        <!-- Pilihan Default UPT Induk per File (Selalu Muncul) -->
                        <div class="mb-6 space-y-3">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200">Default UPT Induk per File (Opsional)</label>
                            <p class="text-xs text-gray-400 dark:text-gray-400">Jika file CSV tidak memiliki kolom Induk, Anda bisa tentukan UPT default khusus untuk masing-masing file di bawah ini.</p>
                            
                            <div id="dynamicUptContainer" class="space-y-2">
                                <div class="flex items-center justify-between gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg text-xs">
                                    <div class="truncate font-medium text-gray-400 italic max-w-[50%]">
                                        Belum ada file dipilih
                                    </div>
                                    <div class="w-1/2">
                                        <select disabled class="w-full border border-gray-300 dark:border-gray-600 rounded-md p-1.5 text-xs bg-gray-100 dark:bg-gray-800 text-gray-400 cursor-not-allowed">
                                            <option>-- Pilih UPT Default (Opsional) --</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex items-center justify-end gap-3 border-t border-gray-200 dark:border-gray-700 pt-4">
                            <a href="{{ route('manage-unit') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Batal</a>
                            <button type="submit" class="bg-[#004A54] hover:bg-[#003840] text-white px-5 py-2 rounded-lg text-sm font-medium transition-colors">
                                Proses Import Semua
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    const adminMenuToggle = document.getElementById('adminMenuToggle');
    const adminSubmenu = document.getElementById('adminSubmenu');
    const adminMenuChevron = document.getElementById('adminMenuChevron');

    if (adminMenuToggle && adminSubmenu) {
        adminMenuToggle.addEventListener('click', () => {
            adminSubmenu.classList.toggle('hidden');
            adminMenuChevron.classList.toggle('rotate-180');
        });
    }

    // Data UPT dari Backend
    const uptOptions = [
        @foreach($upts as $upt)
            { id: "{{ $upt->id }}", name: "{{ $upt->name }}" },
        @endforeach
    ];

    const fileInput = document.getElementById('fileInput');
    const fileListContainer = document.getElementById('fileListContainer');
    const fileNamesList = document.getElementById('fileNamesList');
    const fileCount = document.getElementById('fileCount');
    const dynamicUptContainer = document.getElementById('dynamicUptContainer');

    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            fileNamesList.innerHTML = '';
            dynamicUptContainer.innerHTML = '';
            const files = e.target.files;
            
            if (files.length > 0) {
                fileListContainer.classList.remove('hidden');
                fileCount.textContent = files.length;
                
                for (let i = 0; i < files.length; i++) {
                    const fileName = files[i].name;

                    // 1. Preview nama file
                    const li = document.createElement('li');
                    li.textContent = fileName;
                    fileNamesList.appendChild(li);

                    // 2. Card Select UPT per File
                    const wrapper = document.createElement('div');
                    wrapper.className = 'flex items-center justify-between gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg text-xs';

                    const labelDiv = document.createElement('div');
                    labelDiv.className = 'truncate font-medium text-gray-700 dark:text-gray-200 max-w-[50%]';
                    labelDiv.textContent = '📄 ' + fileName;
                    labelDiv.title = fileName;

                    const selectDiv = document.createElement('div');
                    selectDiv.className = 'w-1/2';

                    const select = document.createElement('select');
                    select.name = 'default_upt_id[]';
                    select.className = 'w-full border border-gray-300 dark:border-gray-600 rounded-md p-1.5 text-xs bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-[#004A54]';

                    // Opsi default kosong
                    const defaultOpt = document.createElement('option');
                    defaultOpt.value = '';
                    defaultOpt.textContent = '-- Pilih UPT Default (Opsional) --';
                    select.appendChild(defaultOpt);

                    // Masukkan opsi UPT
                    uptOptions.forEach(upt => {
                        const opt = document.createElement('option');
                        opt.value = upt.id;
                        opt.textContent = upt.name;
                        select.appendChild(opt);
                    });

                    selectDiv.appendChild(select);
                    wrapper.appendChild(labelDiv);
                    wrapper.appendChild(selectDiv);

                    dynamicUptContainer.appendChild(wrapper);
                }
            } else {
                fileListContainer.classList.add('hidden');
                
                // Tampilkan kembali placeholder kosong jika file dihapus/tidak dipilih
                const wrapper = document.createElement('div');
                wrapper.className = 'flex items-center justify-between gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg text-xs';

                const labelDiv = document.createElement('div');
                labelDiv.className = 'truncate font-medium text-gray-400 italic max-w-[50%]';
                labelDiv.textContent = 'Belum ada file dipilih';

                const selectDiv = document.createElement('div');
                selectDiv.className = 'w-1/2';

                const select = document.createElement('select');
                select.disabled = true;
                select.className = 'w-full border border-gray-300 dark:border-gray-600 rounded-md p-1.5 text-xs bg-gray-100 dark:bg-gray-800 text-gray-400 cursor-not-allowed';
                
                const opt = document.createElement('option');
                opt.textContent = '-- Pilih UPT Default (Opsional) --';
                select.appendChild(opt);

                selectDiv.appendChild(select);
                wrapper.appendChild(labelDiv);
                wrapper.appendChild(selectDiv);
                dynamicUptContainer.appendChild(wrapper);
            }
        });
    }
</script>
@endsection