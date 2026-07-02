@extends('layouts.app', ['title' => 'Tambah Pengguna Baru — PLN Financial'])

@section('content')
<div class="min-h-screen flex bg-gray-50">

    {{-- ===================== SIDEBAR ===================== --}}
    <aside class="hidden lg:flex lg:flex-col lg:w-60 bg-pln-800 text-white shrink-0">
        <div class="px-6 py-6">
            <p class="font-bold text-lg leading-tight">PLN Financial</p>
            <p class="text-[10px] tracking-[0.2em] text-accent-400">UTILITY MANAGEMENT</p>
        </div>

        <nav class="flex-1 px-4 space-y-1 mt-2">
            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium text-gray-200 hover:bg-white/5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                </svg>
                Dashboard
            </a>

            {{-- Admin Menu (Active) --}}
            <div>
                <button type="button" class="w-full flex items-center justify-between px-3 py-2.5 rounded-md text-sm font-medium text-white bg-white/5">
                    <span class="flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0" />
                        </svg>
                        Admin
                    </span>
                    <svg class="w-3.5 h-3.5 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>
                <div class="ml-7 mt-1 border-l border-white/10 pl-3">
                    <a href="{{ route('manage-user') }}" class="relative block px-2 py-2 text-sm text-white font-medium before:absolute before:-left-[13px] before:top-1/2 before:-translate-y-1/2 before:w-1 before:h-5 before:bg-[#e8e14a] before:rounded-r">Manage User</a>
                </div>
            </div>

            {{-- Settings --}}
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium text-gray-200 hover:bg-white/5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 0 1 0 .255c-.007.378.138.751.43.991l1.005.828c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 0 1 0-.255c.007-.378-.138-.751-.43-.991l-1.004-.828a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.281Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
                Settings
            </a>
        </nav>

        {{-- Profile Card Kiri Bawah --}}
        <div class="p-4 border-t border-white/10">
            <div class="flex items-center gap-3 px-2 py-2 rounded-md bg-white/5">
                <div class="w-8 h-8 rounded-full bg-[#e8e14a] text-[#064e57] font-bold text-xs flex items-center justify-center">
                    AC
                </div>
                <div class="leading-tight">
                    <p class="text-sm font-semibold text-white">Admin Central</p>
                    <p class="text-[11px] text-gray-300">admin@pln.co.id</p>
                </div>
            </div>
            <div class="mt-3 space-y-1">
                <a href="#" class="flex items-center gap-2 px-2 py-1.5 text-sm text-gray-300 hover:text-white">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="9" stroke-linecap="round" stroke-linejoin="round" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.5 9a2.5 2.5 0 1 1 3.5 2.29c-.7.32-1 .8-1 1.71M12 17h.01" />
                    </svg>
                    Help
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-2 py-1.5 text-sm text-gray-300 hover:text-white text-left">
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
    <div class="flex-1 min-w-0 flex flex-col justify-between">
        <div>
            {{-- Top bar --}}
            <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between gap-4">
                <div class="relative w-full max-w-sm">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <circle cx="11" cy="11" r="7" stroke-linecap="round" stroke-linejoin="round" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="m20 20-3.5-3.5" />
                        </svg>
                    </span>
                    <input type="text" placeholder="Cari di pengaturan..."
                        class="w-full rounded-md border border-gray-200 bg-gray-50 py-2 pl-10 pr-3 text-sm text-gray-700 focus:border-[#064e57] focus:bg-white focus:outline-none">
                </div>

                <div class="flex items-center gap-5 shrink-0">
                    <button type="button" class="relative text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.85 23.85 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                        </svg>
                        <span class="absolute -top-0.5 -right-0.5 w-2 h-2 rounded-full bg-red-500"></span>
                    </button>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-semibold text-gray-700">Profile</span>
                        <div class="w-8 h-8 rounded-full bg-pln-800 text-white flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0" />
                            </svg>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Content Area --}}
            <main class="p-6 space-y-4">
                {{-- Breadcrumbs --}}
                <div class="text-xs text-gray-500 font-medium flex items-center gap-1.5">
                    <a href="{{ route('manage-user') }}" class="hover:underline text-[#064e57]">Manage User</a>
                    <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-gray-400">Tambah Pengguna</span>
                </div>

                <h1 class="text-2xl font-bold text-[#063333]">Tambah Pengguna Baru</h1>

                {{-- Form Card --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    {{-- Header Card --}}
                    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-gray-800">Informasi Akun</h2>
                            <p class="text-xs text-gray-500">Lengkapi detail profil dan peran pengguna di bawah ini.</p>
                        </div>
                    </div>

                    {{-- Body Form --}}
                    <form action="{{ route('manage-user.store') }}" method="POST" class="p-6 space-y-5">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            {{-- Nama Lengkap --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-gray-700 tracking-wide">Nama Lengkap</label>
                                <div class="relative">
                                    <input type="text" name="name" placeholder="Masukkan nama lengkap" required
                                        class="w-full rounded-md border border-gray-300 py-2.5 pl-3 pr-3 text-sm text-gray-800 placeholder-gray-400 focus:border-[#064e57] focus:outline-none focus:ring-1 focus:ring-[#064e57]">
                                </div>
                            </div>

                            {{-- Alamat Email --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-gray-700 tracking-wide">Alamat Email</label>
                                <input type="email" name="email" placeholder="example@pln.co.id" required
                                    class="w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm text-gray-800 placeholder-gray-400 focus:border-[#064e57] focus:outline-none focus:ring-1 focus:ring-[#064e57]">
                            </div>

                            {{-- NIP --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-gray-700 tracking-wide">Nomor Induk Pegawai (NIP)</label>
                                <input type="text" name="nip" placeholder="Contoh: 19920815XXXX" required
                                    class="w-full rounded-md border border-gray-300 py-2.5 px-3 text-sm text-gray-800 placeholder-gray-400 focus:border-[#064e57] focus:outline-none focus:ring-1 focus:ring-[#064e57]">
                            </div>

                            {{-- Departemen --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-gray-700 tracking-wide">Departemen</label>
                                <select name="department" required class="w-full rounded-md border border-gray-300 bg-white py-2.5 px-3 text-sm text-gray-800 focus:outline-none focus:border-[#064e57] focus:ring-1 focus:ring-[#064e57]">
                                    <option value="" disabled selected>Pilih Departemen</option>
                                    <option value="Keuangan">Keuangan</option>
                                    <option value="Sistem Informasi">Sistem Informasi</option>
                                    <option value="SDM">Sumber Daya Manusia (SDM)</option>
                                </select>
                            </div>

                            {{-- Peran/Role --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-gray-700 tracking-wide">Peran/Role</label>
                                <select name="role" required class="w-full rounded-md border border-gray-300 bg-white py-2.5 px-3 text-sm text-gray-800 focus:outline-none focus:border-[#064e57] focus:ring-1 focus:ring-[#064e57]">
                                    <option value="" disabled selected>Pilih Peran</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Manager">Manager</option>
                                    <option value="Staff">Staff</option>
                                </select>
                            </div>

                            {{-- Status Akun --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-gray-700 tracking-wide block">Status Akun</label>
                                <div class="flex items-center gap-4 pt-2">
                                    <input type="radio" name="status" value="Aktif" id="aktif" {{ old('status', 'Aktif') == 'Aktif' ? 'checked' : '' }}>
                                    <label for="aktif">Aktif</label>

                                    <input type="radio" name="status" value="Non-Aktif" id="non-aktif" {{ old('status') == 'Non-Aktif' ? 'checked' : '' }}>
                                    <label for="non-aktif">Non-Aktif</label>
                                </div>
                            </div>
                        </div>

                        {{-- Info Callout --}}
                        <div class="bg-cyan-50/50 border-l-4 border-cyan-500 p-4 rounded-r-md flex gap-3 mt-2">
                            <svg class="w-5 h-5 text-cyan-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-xs text-cyan-800 leading-relaxed">
                                Pengguna baru akan menerima email verifikasi untuk mengatur kata sandi mereka secara mandiri setelah akun dibuat oleh administrator.
                            </p>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                            <a href="{{ route('manage-user') }}" class="border border-gray-300 text-gray-700 px-5 py-2 rounded-md text-sm font-medium hover:bg-gray-50 transition-colors">
                                Batal
                            </a>
                            <button type="submit" class="bg-[#004A54] text-white px-5 py-2 rounded-md text-sm font-medium hover:bg-[#00363d] transition-colors shadow-sm flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                Simpan Pengguna
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>

        {{-- Footer --}}
        <footer class="px-6 py-4 bg-white border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-2 text-[11px] text-gray-500">
            <p>&copy; 2024 PT PLN (PERSERO) - FINANCIAL INTEGRITY MODULE</p>
            <div class="flex gap-4">
                <a href="#" class="hover:underline">Privacy Policy</a>
                <a href="#" class="hover:underline">Support Center</a>
            </div>
        </footer>
    </div>
</div>
@endsection