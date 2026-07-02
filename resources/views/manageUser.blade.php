@extends('layouts.app', ['title' => 'Manage User — PLN Financial'])

@section('content')
<div class="min-h-screen flex bg-gray-50">

    {{-- ===================== SIDEBAR ===================== --}}
    <aside class="hidden lg:flex lg:flex-col lg:w-60 bg-pln-800 text-white shrink-0">
        <div class="px-6 py-6">
            <p class="font-bold text-lg leading-tight">PLN Financial</p>
            <p class="text-[10px] tracking-[0.2em] text-accent-400">UTILITY MANAGEMENT</p>
        </div>

        <nav class="flex-1 px-4 space-y-1 mt-2">
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium text-pln-100 hover:bg-white/5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                </svg>
                Dashboard
            </a>

            <div>
                <button type="button" id="adminMenuToggle"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-md text-sm font-medium text-white bg-white/5">
                    <span class="flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0" />
                        </svg>
                        Admin
                    </span>
                    <svg id="adminMenuChevron" class="w-3.5 h-3.5 transition-transform duration-200 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>
                <div id="adminSubmenu" class="ml-7 mt-1 border-l border-white/10 pl-3">
                    <a href="#" class="relative block px-2 py-2 text-sm text-white font-medium before:absolute before:-left-[13px] before:top-1/2 before:-translate-y-1/2 before:w-1 before:h-5 before:bg-accent-400 before:rounded-r">Manage User</a>
                </div>
            </div>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium text-pln-100 hover:bg-white/5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 0 1 0 .255c-.007.378.138.751.43.991l1.005.828c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 0 1 0-.255c.007-.378-.138-.751-.43-.991l-1.004-.828a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.281Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
                Settings
            </a>
        </nav>

        <div class="p-4 border-t border-white/10">
            <div class="flex items-center gap-3 px-2 py-2 rounded-md bg-white/5">
                <div class="w-8 h-8 rounded-full bg-accent-400 text-pln-800 font-bold text-xs flex items-center justify-center">
                    AC
                </div>
                <div class="leading-tight">
                    <p class="text-sm font-semibold text-white">Admin Central</p>
                    <p class="text-[11px] text-pln-100/70">admin@pln.co.id</p>
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
        <header class="bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between gap-4">
            <div class="relative w-full max-w-sm">
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <circle cx="11" cy="11" r="7" stroke-linecap="round" stroke-linejoin="round" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="m20 20-3.5-3.5" />
                    </svg>
                </span>
                <input type="text" placeholder="Search system resources..."
                    class="w-full rounded-md border border-gray-200 bg-gray-50 py-2 pl-10 pr-3 text-sm text-gray-700 placeholder-gray-400 focus:border-pln-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-pln-700/20">
            </div>

            <div class="flex items-center gap-5 shrink-0">
                <button type="button" class="relative text-gray-500 hover:text-gray-700">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.85 23.85 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
                    <span class="absolute -top-0.5 -right-0.5 w-2 h-2 rounded-full bg-red-500"></span>
                </button>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold text-pln-800">Profile</span>
                    <div class="w-8 h-8 rounded-full bg-pln-800 text-white flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0" />
                        </svg>
                    </div>
                </div>
            </div>
        </header>

        {{-- Content Area --}}
        <main class="p-6 space-y-5">
            
            {{-- Header Title & Action Button --}}
            <div class="flex items-center justify-between">
                <h1 class="text-lg font-bold text-pln-800 tracking-wide">Manage User</h1>
                <a href="{{ route('manage-user.create') }}" class="inline-flex items-center justify-center bg-[#004A54] text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-[#00363d] transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add New User
                </a>
            </div>
            {{-- Pembungkus Utama Konten Halaman (Pastikan flex-col agar mengalir ke bawah) --}}
            <div class="w-full flex flex-col p-6 space-y-6">
                {{-- 2. Bagian Filter & Cari Pengguna (Fixed) --}}
                <div class="w-full bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                    <form action="{{ route('manage-user') }}" method="GET" class="w-full flex flex-row flex-wrap items-center gap-4">
                        
                        {{-- Input Pencarian Nama/Email --}}
                        <div class="flex-1 min-w-[200px] relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h12M4 18h8" />
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." 
                                class="w-full rounded-lg border border-gray-300 py-2.5 pl-9 pr-4 text-sm text-gray-700 placeholder-gray-400 focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54] transition-colors">
                        </div>

                        {{-- Dropdown Filter Role --}}
                        <div class="flex items-center gap-2 shrink-0">
                            <label class="text-sm font-semibold text-gray-700 whitespace-nowrap">Role:</label>
                            <div class="relative w-28">
                                <select name="role" onchange="this.form.submit()" class="w-full appearance-none rounded-lg border border-gray-300 py-2 pl-3 pr-7 text-sm text-gray-700 bg-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54] cursor-pointer">
                                    <option value="All" {{ request('role') == 'All' ? 'selected' : '' }}>All</option>
                                    <option value="Admin" {{ request('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="Manager" {{ request('role') == 'Manager' ? 'selected' : '' }}>Manager</option>
                                    <option value="Staff" {{ request('role') == 'Staff' ? 'selected' : '' }}>Staff</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-gray-400">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </div>
                        </div>

                        {{-- Dropdown Filter Status --}}
                        <div class="flex items-center gap-2 shrink-0">
                            <label class="text-sm font-semibold text-gray-700 whitespace-nowrap">Status:</label>
                            <div class="relative w-28">
                                <select name="status" onchange="this.form.submit()" class="w-full appearance-none rounded-lg border border-gray-300 py-2 pl-3 pr-7 text-sm text-gray-700 bg-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54] cursor-pointer">
                                    <option value="All" {{ request('status') == 'All' ? 'selected' : '' }}>All</option>
                                    <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Non-Aktif" {{ request('status') == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-gray-400">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Reset --}}
                        <div class="shrink-0">
                            <a href="{{ route('manage-user') }}" class="border-2 border-[#004A54] text-[#004A54] px-5 py-1.5 rounded-lg text-sm font-bold hover:bg-cyan-50/50 transition-all block text-center tracking-wide">
                                Reset
                            </a>
                        </div>

                    </form>
                </div>

                {{-- 3. Bagian Tabel / Daftar Pengguna (Akan otomatis berada di bawah Filter) --}}
                <div class="w-full bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                    {{-- Taruh baris elemen tabel/list pengguna kamu di dalam sini --}}
                    {{-- Contoh: <table class="w-full text-left"> ... </table> --}}
                </div>

            </div>

            {{-- ===================== TABLE CONTAINER (KOTAK DARI FIGMA) ===================== --}}
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                
                {{-- HEADER TABEL (Sesuai Desain Figma) --}}
                <div class="grid grid-cols-5 bg-gray-50 px-6 py-3 border-b border-gray-200 text-xs font-bold uppercase tracking-wider text-gray-500">
                    <div>User</div>
                    <div>Email</div>
                    <div>Role</div>
                    <div>Status</div>
                    <div class="text-center">Action</div>
                </div>

                {{-- DATA DATA PERULANGAN USER --}}
<div class="divide-y divide-gray-200">
                    @forelse($users as $user)
                        <div class="grid grid-cols-5 items-center px-6 py-4 text-sm hover:bg-gray-50/50 transition-colors">
                            
                            {{-- KOLOM 1: USER (Avatar Bulat, Nama, ID/NIP) --}}
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-full bg-gray-100 text-gray-700 font-bold text-xs flex items-center justify-center shrink-0 uppercase border border-gray-200">
                                    {{ implode('', array_map(function($v) { return $v ? $v[0] : ''; }, explode(' ', $user->name))) }}
                                </div>
                                <div class="leading-tight min-w-0">
                                    <p class="font-semibold text-[#004A54] truncate">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-400 truncate">ID: {{ $user->nip ?? '102022300029' }}</p>
                                </div>
                            </div>

                            {{-- KOLOM 2: EMAIL --}}
                            <div class="text-gray-600 truncate pr-4">
                                {{ $user->email }}
                            </div>

                            {{-- KOLOM PERAN/ROLE --}}
                            <div>
                                @if(strcasecmp($user->role, 'Admin') === 0)
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-md bg-yellow-100 text-yellow-800 uppercase tracking-wide">Admin</span>
                                @elseif(strcasecmp($user->role, 'Manager') === 0)
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-md bg-purple-100 text-purple-800 uppercase tracking-wide">Manager</span>
                                @else
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-md bg-gray-100 text-gray-600 uppercase tracking-wide">Staff</span>
                                @endif
                            </div>

                            {{-- KOLOM STATUS --}}
                            <div>
                                @if(strcasecmp($user->status, 'Aktif') === 0)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-cyan-50 text-cyan-700 border border-cyan-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-500"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        Non-Aktif
                                    </span>
                                @endif
                            </div>

                            {{-- KOLOM 5: ACTION (Tombol Edit & Tombol Hapus Berdampingan dengan Proteksi Seeder) --}}
                            <div class="flex items-center justify-center gap-2">
                                {{-- Tombol Edit (Tetap Ada untuk Semua User) --}}
                                <a href="#" class="text-gray-400 hover:text-[#004A54] p-1.5 rounded-md hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </a>

                                {{-- Cek Apakah User Merupakan Admin Master / Seeder Bawaan --}}
                                @if($user->email === 'admin@pln.co.id')
                                    {{-- Jika Admin PLN, Berikan Ikon Terkunci / Disabled agar tidak bisa dihapus --}}
                                    <span class="text-gray-300 p-1.5 cursor-not-allowed" title="User bawaan sistem tidak dapat dihapus">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </span>
                                @else
                                    {{-- Jika User Biasa, Tampilkan Tombol Hapus Seperti Biasa --}}
                                    <form action="{{ route('manage-user.destroy', $user->id) }}" method="POST" 
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna {{ $user->name }}?')"
                                        class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 p-1.5 rounded-md hover:bg-gray-50 transition-colors duration-150 focus:outline-none">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>

                        </div>
                    @empty
                        <div class="p-12 text-center text-gray-400 bg-white">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            Belum ada data pengguna yang cocok ditemukan.
                        </div>
                    @endforelse
                </div>

                {{-- FOOTER / PAGINATION --}}
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-xs text-gray-500 font-medium">
                        Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} pengguna
                    </div>
                    <div class="laravel-pagination">
                        {{ $users->links() }}
                    </div>
                </div>

            </div>
            {{-- ===================== END OF TABLE CONTAINER ===================== --}}

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
</script>
@endsection