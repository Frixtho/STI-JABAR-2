@extends('layouts.app', ['title' => 'Tambah Pengguna Baru — PLN Financial'])

@section('content')
<div class="min-h-screen flex bg-gray-50 dark:bg-gray-900">

    {{-- ===================== MAIN CONTENT ===================== --}}
    <div class="flex-1 min-w-0 flex flex-col justify-between">
        <div>
            {{-- Top bar --}}
            <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex items-center justify-between gap-4">
                <div class="relative w-full max-w-sm">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <circle cx="11" cy="11" r="7" stroke-linecap="round" stroke-linejoin="round" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="m20 20-3.5-3.5" />
                        </svg>
                    </span>
                    <input type="text" placeholder="Cari di pengaturan..."
                        class="w-full rounded-md border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 py-2 pl-10 pr-3 text-sm text-gray-700 dark:text-gray-200 focus:border-[#064e57] focus:bg-white dark:focus:bg-gray-700 focus:outline-none">
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
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ auth()->user()->name }}</p>
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
            <main class="p-6 space-y-4">
                {{-- Breadcrumbs --}}
                <div class="text-xs text-gray-500 dark:text-gray-400 font-medium flex items-center gap-1.5">
                    <a href="{{ route('manage-user') }}" class="hover:underline text-[#064e57] dark:text-accent-400">Manage User</a>
                    <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-gray-400">Tambah Pengguna</span>
                </div>

                <h1 class="text-2xl font-bold text-[#063333] dark:text-white">Tambah Pengguna Baru</h1>

                @if ($errors->any())
                    <div class="rounded-md border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/30 px-4 py-3 text-sm text-red-700 dark:text-red-400">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Form Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                    {{-- Header Card --}}
                    <div class="p-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-300">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-gray-800 dark:text-gray-100">Informasi Akun</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Lengkapi detail profil dan peran pengguna di bawah ini.</p>
                        </div>
                    </div>

                    {{-- Body Form --}}
                    <form action="{{ route('manage-user.store') }}" method="POST" class="p-6 space-y-5">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            {{-- Nama Lengkap --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300 tracking-wide">Nama Lengkap</label>
                                <div class="relative">
                                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required
                                        class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 pl-3 pr-3 text-sm text-gray-800 dark:text-white placeholder-gray-400 focus:border-[#064e57] focus:outline-none focus:ring-1 focus:ring-[#064e57]">
                                </div>
                            </div>

                            {{-- Alamat Email --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300 tracking-wide">Alamat Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="example@pln.co.id" required
                                    class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white placeholder-gray-400 focus:border-[#064e57] focus:outline-none focus:ring-1 focus:ring-[#064e57]">
                            </div>

                            {{-- NIP --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300 tracking-wide">Nomor Induk Pegawai (NIP)</label>
                                <input type="text" name="nip" value="{{ old('nip') }}" placeholder="Contoh: 19920815XXXX" required
                                    class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white placeholder-gray-400 focus:border-[#064e57] focus:outline-none focus:ring-1 focus:ring-[#064e57]">
                            </div>

                            {{-- Departemen --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300 tracking-wide">Departemen</label>
                                <select name="department" required class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:outline-none focus:border-[#064e57] focus:ring-1 focus:ring-[#064e57]">
                                    <option value="" disabled {{ old('department') ? '' : 'selected' }}>Pilih Departemen</option>
                                    <option value="Keuangan" @selected(old('department') === 'Keuangan')>Keuangan</option>
                                    <option value="Sistem Informasi" @selected(old('department') === 'Sistem Informasi')>Sistem Informasi</option>
                                    <option value="Sumber Daya Manusia (SDM)" @selected(old('department') === 'Sumber Daya Manusia (SDM)')>Sumber Daya Manusia (SDM)</option>
                                </select>
                            </div>

                            {{-- Peran/Role --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300 tracking-wide">Peran/Role</label>
                                <select name="role" required class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:outline-none focus:border-[#064e57] focus:ring-1 focus:ring-[#064e57]">
                                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih Peran</option>
                                    <option value="Admin" @selected(old('role') === 'Admin')>Admin</option>
                                    <option value="Manager" @selected(old('role') === 'Manager')>Manager</option>
                                    <option value="Staff" @selected(old('role') === 'Staff')>Staff</option>
                                </select>
                            </div>

                            {{-- Status Akun --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-gray-700 dark:text-gray-300 tracking-wide block">Status Akun</label>
                                <div class="flex items-center gap-4 pt-2">
                                    <input type="radio" name="status" value="Aktif" id="aktif" {{ old('status', 'Aktif') == 'Aktif' ? 'checked' : '' }}>
                                    <label for="aktif" class="text-sm text-gray-700 dark:text-gray-300">Aktif</label>

                                    <input type="radio" name="status" value="Non-Aktif" id="non-aktif" {{ old('status') == 'Non-Aktif' ? 'checked' : '' }}>
                                    <label for="non-aktif" class="text-sm text-gray-700 dark:text-gray-300">Non-Aktif</label>
                                </div>
                            </div>
                        </div>

                        {{-- Info Callout --}}
                        <div class="bg-cyan-50/50 dark:bg-cyan-900/20 border-l-4 border-cyan-500 p-4 rounded-r-md flex gap-3 mt-2">
                            <svg class="w-5 h-5 text-cyan-600 dark:text-cyan-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-xs text-cyan-800 dark:text-cyan-300 leading-relaxed">
                                Pengguna baru akan menerima email verifikasi untuk mengatur kata sandi mereka secara mandiri setelah akun dibuat oleh administrator.
                            </p>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('manage-user') }}" class="border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-5 py-2 rounded-md text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
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
        <footer class="px-6 py-4 bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-2 text-[11px] text-gray-500 dark:text-gray-400">
            <p>&copy; 2024 PT PLN (PERSERO) - FINANCIAL INTEGRITY MODULE</p>
            <div class="flex gap-4">
                <a href="#" class="hover:underline">Privacy Policy</a>
                <a href="#" class="hover:underline">Support Center</a>
            </div>
        </footer>
    </div>
</div>
@endsection