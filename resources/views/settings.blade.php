@extends('layouts.app', ['title' => 'Pengaturan — PLN Financial'])

@section('content')
<div class="min-h-screen flex">

    {{-- ===================== MAIN CONTENT ===================== --}}
    <div class="flex-1 min-w-0 flex flex-col">

        {{-- Top bar --}}
        <header class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 px-6 py-4 flex items-center justify-between gap-4">
            <div class="relative w-full max-w-sm">
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <circle cx="11" cy="11" r="7" stroke-linecap="round" stroke-linejoin="round" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="m20 20-3.5-3.5" />
                    </svg>
                </span>
                <input type="text" placeholder="Cari di pengaturan..."
                    class="w-full rounded-md border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 py-2 pl-10 pr-3 text-sm text-gray-700 dark:text-gray-200 placeholder-gray-400 focus:border-pln-700 focus:bg-white dark:focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-pln-700/20">
            </div>

            <div class="flex items-center gap-5 shrink-0">
                <button type="button" class="relative text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.85 23.85 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
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

        <main class="p-6 flex-1">

            <div>
                <h1 class="text-2xl font-bold text-pln-800 dark:text-white">Pengaturan Sistem</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Kelola preferensi akun, keamanan, dan konfigurasi aplikasi
                </p>
            </div>

            {{-- Pesan Sukses Profil --}}
            @if (session('status') && session('status') !== 'password-updated')
                <div class="mt-4 rounded-md border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/30 px-4 py-3 text-sm text-green-700 dark:text-green-400">
                    {{ session('status') }}
                </div>
            @endif

            <div class="mt-6 grid grid-cols-1 lg:grid-cols-[220px_1fr] gap-6 items-start">

                {{-- ===================== SETTINGS TABS ===================== --}}
                <nav class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-2 space-y-1">
                    <button type="button" data-tab="profil"
                        class="settings-tab w-full flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium bg-pln-800 text-white">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0" />
                        </svg>
                        Profil Saya
                    </button>
                    <button type="button" data-tab="keamanan"
                        class="settings-tab w-full flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 2.25c-3.14 2.1-6.46 2.79-9 2.79v6.02c0 5.7 3.87 9.8 9 10.94 5.13-1.14 9-5.24 9-10.94V5.04c-2.54 0-5.86-.69-9-2.79Z" />
                        </svg>
                        Keamanan
                    </button>
                    <button type="button" data-tab="preferensi"
                        class="settings-tab w-full flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 0 1 0 .255c-.007.378.138.751.43.991l1.005.828c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 0 1 0-.255c.007-.378-.138-.751-.43-.991l-1.004-.828a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.281Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        Preferensi Sistem
                    </button>
                </nav>

                {{-- ===================== TAB CONTENT ===================== --}}
                <div>

                    {{-- 1. Profil Saya --}}
                    <div id="tab-profil" class="settings-panel bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                        <form method="POST" action="{{ route('settings.update') }}">
                            @csrf
                            @method('PATCH')

                            <div class="flex items-start gap-4">
                                <div class="relative shrink-0">
                                    <div class="w-16 h-16 rounded-xl bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 flex items-center justify-center overflow-hidden">
                                        <svg class="w-9 h-9 text-gray-300 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0" />
                                        </svg>
                                    </div>
                                    <button type="button" class="absolute -bottom-1.5 -right-1.5 w-6 h-6 rounded-full bg-pln-800 text-white flex items-center justify-center border-2 border-white dark:border-gray-800">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                        </svg>
                                    </button>
                                </div>
                                <div>
                                    <h2 class="text-base font-bold text-pln-800 dark:text-white">Informasi Personal</h2>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Perbarui foto profil dan detail identitas Anda.</p>
                                </div>
                            </div>

                            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label for="name" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Full Name</label>
                                    <input id="name" name="name" type="text" value="{{ old('name', auth()->user()->name) }}"
                                        class="mt-1.5 w-full rounded-md border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-pln-700 focus:outline-none focus:ring-2 focus:ring-pln-700/20">
                                </div>
                                <div>
                                    <label for="nip" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Employee ID (NIP)</label>
                                    <input id="nip" type="text" value="{{ auth()->user()->nip ?? '—' }}" disabled
                                        class="mt-1.5 w-full rounded-md border border-gray-200 dark:border-gray-600 bg-gray-100 dark:bg-gray-700/50 py-2.5 px-3 text-sm text-gray-500 dark:text-gray-400 cursor-not-allowed">
                                </div>
                                <div>
                                    <label for="email" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Email Address</label>
                                    <input id="email" name="email" type="email" value="{{ old('email', auth()->user()->email) }}"
                                        class="mt-1.5 w-full rounded-md border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-pln-700 focus:outline-none focus:ring-2 focus:ring-pln-700/20">
                                </div>
                                <div>
                                    <label for="department" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Department</label>
                                    <input id="department" type="text" value="{{ auth()->user()->department ?? '—' }}" disabled
                                        class="mt-1.5 w-full rounded-md border border-gray-200 dark:border-gray-600 bg-gray-100 dark:bg-gray-700/50 py-2.5 px-3 text-sm text-gray-500 dark:text-gray-400 cursor-not-allowed">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Role</label>
                                    <div class="mt-1.5">
                                        <span class="inline-flex items-center text-xs font-bold uppercase tracking-wide px-2.5 py-1.5 rounded-md
                                            {{ strcasecmp(auth()->user()->role, 'Admin') === 0 ? 'bg-accent-400 text-pln-800' : 'bg-gray-100 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400' }}">
                                            {{ auth()->user()->role }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 flex items-center justify-end gap-3">
                                <button type="reset" class="px-4 py-2.5 rounded-md text-sm font-medium text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    Batalkan Perubahan
                                </button>
                                <button type="submit" class="px-4 py-2.5 rounded-md text-sm font-semibold text-white bg-pln-800 hover:bg-pln-700">
                                    Simpan Pengaturan
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- 3. Keamanan --}}
                    <div id="tab-keamanan" class="settings-panel hidden space-y-4">
                        
                        {{-- Pesan Sukses Khusus Ubah Password --}}
                        @if (session('status') === 'password-updated')
                            <div class="rounded-md border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/30 px-4 py-3 text-sm text-green-700 dark:text-green-400">
                                Kata sandi Anda berhasil diperbarui!
                            </div>
                        @endif

                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-pln-700 dark:text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 0h10.5a1.5 1.5 0 0 1 1.5 1.5v7.5a1.5 1.5 0 0 1-1.5 1.5H6.75a1.5 1.5 0 0 1-1.5-1.5v-7.5a1.5 1.5 0 0 1 1.5-1.5Z" />
                                </svg>
                                <h3 class="text-sm font-bold text-gray-700 dark:text-gray-200">Ubah Kata Sandi</h3>
                            </div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Pastikan kata sandi Anda kuat dan unik.</p>

                            <form method="POST" action="{{ route('settings.password') }}" class="mt-4 space-y-4">
                                @csrf
                                @method('PATCH')

                                <div>
                                    <label for="current_password" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Kata Sandi Saat Ini</label>
                                    <input id="current_password" name="current_password" type="password" required
                                        class="mt-1.5 w-full rounded-md border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-pln-700 focus:outline-none focus:ring-2 focus:ring-pln-700/20">
                                    
                                    {{-- Pesan error jika password lama salah --}}
                                    @error('current_password', 'updatePassword')
                                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                                    @enderror
                                    @error('current_password')
                                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="password" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Kata Sandi Baru</label>
                                        <input id="password" name="password" type="password" required minlength="8"
                                            class="mt-1.5 w-full rounded-md border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-pln-700 focus:outline-none focus:ring-2 focus:ring-pln-700/20">
                                        
                                        {{-- Pesan error jika password baru tidak valid --}}
                                        @error('password', 'updatePassword')
                                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                                        @enderror
                                        @error('password')
                                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="password_confirmation" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Konfirmasi Kata Sandi Baru</label>
                                        <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8"
                                            class="mt-1.5 w-full rounded-md border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-pln-700 focus:outline-none focus:ring-2 focus:ring-pln-700/20">
                                    </div>
                                </div>

                                <div class="flex items-center justify-end gap-3 pt-2">
                                    <button type="reset" class="px-4 py-2.5 rounded-md text-sm font-medium text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        Batalkan
                                    </button>
                                    <button type="submit" class="px-4 py-2.5 rounded-md text-sm font-semibold text-white bg-pln-800 hover:bg-pln-700">
                                        Perbarui Kata Sandi
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-pln-700 dark:text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
                                </svg>
                                <h3 class="text-sm font-bold text-gray-700 dark:text-gray-200">Sesi Aktif</h3>
                            </div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Riwayat login akun Anda di berbagai perangkat berdasarkan database sistem.</p>

                            <div class="mt-4 divide-y divide-gray-100 dark:divide-gray-700">
                                
                                {{-- Looping Data Session dari Database --}}
                                @foreach ($sessions as $session)
                                    <div class="flex items-center justify-between py-3.5">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
                                            </svg>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">
                                                    {{ $session->agent }}
                                                    
                                                    {{-- Tanda "SESI INI" jika ID Session cocok --}}
                                                    @if ($session->is_current_device)
                                                        <span class="ml-1.5 inline-flex text-[10px] font-semibold text-cyan-700 dark:text-cyan-300 bg-cyan-50 dark:bg-cyan-900/30 rounded-full px-2 py-0.5 align-middle">SESI INI</span>
                                                    @endif
                                                </p>
                                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $session->ip_address }} • Aktif {{ $session->last_active }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Tombol Log Out Perangkat Lain (Hanya muncul jika ada lebih dari 1 sesi aktif) --}}
                            @if(count($sessions) > 1)
                                <div class="mt-2 pt-4 border-t border-gray-100 dark:border-gray-700 text-right">
                                    <form method="POST" action="{{ route('settings.sessions.destroy') }}" onsubmit="return confirm('Anda yakin ingin mengeluarkan akun dari semua perangkat lain?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold text-red-600 dark:text-red-400 hover:text-red-700">
                                            Log Out dari Semua Perangkat Lain
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- 4. Preferensi Sistem --}}
                    <div id="tab-preferensi" class="settings-panel hidden bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-pln-800 dark:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 0 1 0 .255c-.007.378.138.751.43.991l1.005.828c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 0 1 0-.255c.007-.378-.138-.751-.43-.991l-1.004-.828a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.281Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <h2 class="text-base font-bold text-pln-800 dark:text-white">Konfigurasi Tampilan</h2>
                        </div>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Sesuaikan bagaimana data dan antarmuka ditampilkan pada perangkat Anda.</p>

                        <hr class="my-6 border-gray-100 dark:border-gray-700">
                        <div class="mt-6">
                            <label class="block text-[11px] font-semibold tracking-wide text-gray-500 dark:text-gray-400 uppercase">
                                Appearance Mode
                            </label>
                            <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-3 max-w-xl">
                                <label class="appearance-option relative flex items-start gap-3 rounded-lg border-2 border-pln-700 bg-pln-50 dark:bg-pln-900/40 dark:border-accent-400 px-4 py-3.5 cursor-pointer">
                                    <input type="radio" name="appearance" value="light" class="sr-only">
                                    <div class="w-9 h-9 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 flex items-center justify-center shrink-0 text-amber-500">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <circle cx="12" cy="12" r="4" stroke-linecap="round" stroke-linejoin="round" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 2.25v1.5M12 20.25v1.5M4.219 4.219l1.06 1.06M18.72 18.72l1.06 1.06M2.25 12h1.5M20.25 12h1.5M4.219 19.781l1.06-1.06M18.72 5.28l1.06-1.06" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800 dark:text-white">Light Mode</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Recommended for high-density data</p>
                                    </div>
                                    <svg class="w-4 h-4 text-pln-700 dark:text-accent-400 absolute top-3 right-3 check-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>
                                </label>

                                <label class="appearance-option relative flex items-start gap-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 px-4 py-3.5 cursor-pointer">
                                    <input type="radio" name="appearance" value="dark" class="sr-only">
                                    <div class="w-9 h-9 rounded-lg bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 flex items-center justify-center shrink-0 text-gray-500 dark:text-gray-300">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800 dark:text-white">Dark Mode</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Optimal for low-light environments</p>
                                    </div>
                                    <span class="radio-dot w-4 h-4 rounded-full border-2 border-gray-300 dark:border-gray-600 absolute top-3 right-3"></span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-8 flex items-center justify-end gap-3">
                            <button type="button" id="cancelPreferensi" class="px-4 py-2.5 rounded-md text-sm font-medium text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                                Batalkan Perubahan
                            </button>
                            <button type="button" id="savePreferensi" class="px-4 py-2.5 rounded-md text-sm font-semibold text-white bg-pln-800 hover:bg-pln-700">
                                Simpan Pengaturan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-gray-400 dark:text-gray-500">
            <span>© {{ date('Y') }} PT PLN (PERSERO) — FINANCIAL INTEGRITY MODULE</span>
            <span class="flex items-center gap-4">
                <a href="#" class="hover:text-gray-600 dark:hover:text-gray-300">Privacy Policy</a>
                <a href="#" class="hover:text-gray-600 dark:hover:text-gray-300">Support Center</a>
            </span>
        </footer>
    </div>
</div>

<script>
    // ===== Admin Dropdown Toggle =====
    const adminMenuToggle = document.getElementById('adminMenuToggle');
    const adminSubmenu = document.getElementById('adminSubmenu');
    const adminMenuChevron = document.getElementById('adminMenuChevron');

    if (adminMenuToggle && adminSubmenu) {
        adminMenuToggle.addEventListener('click', () => {
            adminSubmenu.classList.toggle('hidden');
            if (adminMenuChevron) {
                adminMenuChevron.classList.toggle('rotate-180');
            }
        });
    }

    // ===== Settings Tabs Logic =====
    const tabButtons = document.querySelectorAll('.settings-tab');
    const panels = document.querySelectorAll('.settings-panel');

    tabButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            const target = btn.dataset.tab;

            // Reset semua tombol ke style default (Tidak aktif)
            tabButtons.forEach((b) => {
                b.classList.remove('bg-pln-800', 'text-white');
                b.classList.add('text-gray-600', 'dark:text-gray-300', 'hover:bg-gray-50', 'dark:hover:bg-gray-700');
            });

            // Set tombol yang diklik ke style Aktif
            btn.classList.add('bg-pln-800', 'text-white');
            btn.classList.remove('text-gray-600', 'dark:text-gray-300', 'hover:bg-gray-50', 'dark:hover:bg-gray-700');

            // Sembunyikan semua panel, lalu tampilkan panel yang sesuai dengan tab
            panels.forEach((panel) => {
                if (panel.id === `tab-${target}`) {
                    panel.classList.remove('hidden');
                } else {
                    panel.classList.add('hidden');
                }
            });
        });
    });

    // ===== Auto-Open Keamanan Tab Jika Ada Error Password =====
    @if (session('status') === 'password-updated' || $errors->hasBag('updatePassword') || $errors->has('current_password') || $errors->has('password'))
        document.addEventListener("DOMContentLoaded", () => {
            const keamananTab = document.querySelector('[data-tab="keamanan"]');
            if (keamananTab) keamananTab.click();
        });
    @endif

    // ===== Notification Toggle Switches =====
    document.querySelectorAll('[data-toggle]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const isOn = btn.classList.contains('bg-pln-700');
            const dot = btn.querySelector('.toggle-dot');

            btn.classList.toggle('bg-pln-700', !isOn);
            btn.classList.toggle('bg-gray-200', isOn);
            btn.classList.toggle('dark:bg-gray-600', isOn);
            dot.classList.toggle('translate-x-6', !isOn);
            dot.classList.toggle('translate-x-1', isOn);
        });
    });

    // ===== Dark Mode Preference Logic =====
    function applyTheme(mode) {
        if (mode === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        localStorage.setItem('theme', mode);
    }

    function selectAppearanceCard(mode) {
        document.querySelectorAll('.appearance-option').forEach((label) => {
            const input = label.querySelector('input[type="radio"]');
            const isSelected = input.value === mode;

            label.classList.toggle('border-pln-700', isSelected);
            label.classList.toggle('bg-pln-50', isSelected);
            label.classList.toggle('dark:bg-pln-900/40', isSelected);
            label.classList.toggle('dark:border-accent-400', isSelected);
            label.classList.toggle('border-gray-200', !isSelected);
            label.classList.toggle('dark:border-gray-700', !isSelected);

            input.checked = isSelected;

            const check = label.querySelector('.check-icon');
            const dot = label.querySelector('.radio-dot');
            if (check) check.style.display = isSelected ? '' : 'none';
            if (dot) dot.style.display = isSelected ? 'none' : '';
        });
    }

    const savedTheme = localStorage.getItem('theme') || 'light';
    let pendingTheme = savedTheme;

    document.querySelectorAll('.appearance-option').forEach((label) => {
        label.addEventListener('click', () => {
            pendingTheme = label.querySelector('input[type="radio"]').value;
            selectAppearanceCard(pendingTheme);
        });
    });

    const savePreferensiBtn = document.getElementById('savePreferensi');
    if (savePreferensiBtn) {
        savePreferensiBtn.addEventListener('click', () => {
            applyTheme(pendingTheme);
        });
    }

    const cancelPreferensiBtn = document.getElementById('cancelPreferensi');
    if (cancelPreferensiBtn) {
        cancelPreferensiBtn.addEventListener('click', () => {
            pendingTheme = localStorage.getItem('theme') || 'light';
            selectAppearanceCard(pendingTheme);
        });
    }

    selectAppearanceCard(savedTheme);
</script>
@endsection