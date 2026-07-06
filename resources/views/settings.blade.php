@extends('layouts.app', ['title' => 'Pengaturan — PLN Financial'])

@section('content')
<div class="min-h-screen flex">

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
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-md text-sm font-medium text-pln-100 hover:bg-white/5">
                    <span class="flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0" />
                        </svg>
                        Admin
                    </span>
                    <svg id="adminMenuChevron" class="w-3.5 h-3.5 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>
                <div id="adminSubmenu" class="hidden ml-7 mt-1 border-l border-white/10 pl-3">
                    <a href="{{ route('manage-user') }}" class="block px-2 py-2 text-sm text-pln-100 hover:text-white">Manage User</a>
                </div>
            </div>

            <a href="{{ route('settings') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium bg-white/10 text-white">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 0 1 0 .255c-.007.378.138.751.43.991l1.005.828c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 0 1 0-.255c.007-.378-.138-.751-.43-.991l-1.004-.828a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.281Z" />
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

            @if (session('status'))
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
                    <button type="button" data-tab="notifikasi"
                        class="settings-tab w-full flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.85 23.85 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                        </svg>
                        Notifikasi
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

                    {{-- Profil Saya --}}
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
                                <button type="reset"
                                    class="px-4 py-2.5 rounded-md text-sm font-medium text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    Batalkan Perubahan
                                </button>
                                <button type="submit"
                                    class="px-4 py-2.5 rounded-md text-sm font-semibold text-white bg-pln-800 hover:bg-pln-700">
                                    Simpan Pengaturan
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Notifikasi --}}
                    <div id="tab-notifikasi" class="settings-panel hidden bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-pln-800 dark:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.85 23.85 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                            </svg>
                            <h2 class="text-base font-bold text-pln-800 dark:text-white">Notification Settings</h2>
                        </div>
                        <p class="text-[11px] font-semibold tracking-wide text-gray-400 dark:text-gray-500 uppercase mt-1">Manage how you receive alerts and reports</p>

                        <div class="mt-6 flex items-center gap-2">
                            <svg class="w-4 h-4 text-pln-700 dark:text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                            <h3 class="text-sm font-bold text-gray-700 dark:text-gray-200">Email Notifications</h3>
                        </div>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Stay updated with important documents and activities via email.</p>

                        <div class="mt-4 space-y-1">
                            <div class="flex items-center justify-between rounded-lg bg-gray-50 dark:bg-gray-700/50 px-4 py-3.5">
                                <div>
                                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">Monthly Reports</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Receive automated financial consolidation reports every month.</p>
                                </div>
                                <button type="button" data-toggle class="toggle-switch relative inline-flex h-6 w-11 items-center rounded-full bg-pln-700 transition-colors shrink-0 ml-4">
                                    <span class="toggle-dot inline-block h-4 w-4 transform rounded-full bg-white translate-x-6 transition-transform"></span>
                                </button>
                            </div>

                            <div class="flex items-center justify-between rounded-lg bg-gray-50 dark:bg-gray-700/50 px-4 py-3.5">
                                <div>
                                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">Transaction Alerts</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Get notified for any transaction exceeding the defined threshold.</p>
                                </div>
                                <button type="button" data-toggle class="toggle-switch relative inline-flex h-6 w-11 items-center rounded-full bg-pln-700 transition-colors shrink-0 ml-4">
                                    <span class="toggle-dot inline-block h-4 w-4 transform rounded-full bg-white translate-x-6 transition-transform"></span>
                                </button>
                            </div>

                            <div class="flex items-center justify-between rounded-lg px-4 py-3.5">
                                <div>
                                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">System Updates</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Information about scheduled maintenance and module upgrades.</p>
                                </div>
                                <button type="button" data-toggle class="toggle-switch relative inline-flex h-6 w-11 items-center rounded-full bg-gray-200 dark:bg-gray-600 transition-colors shrink-0 ml-4">
                                    <span class="toggle-dot inline-block h-4 w-4 transform rounded-full bg-white translate-x-1 transition-transform"></span>
                                </button>
                            </div>
                        </div>

                        <hr class="my-6 border-gray-100 dark:border-gray-700">

                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-pln-700 dark:text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 0-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                            </svg>
                            <h3 class="text-sm font-bold text-gray-700 dark:text-gray-200">Push Notifications</h3>
                        </div>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Real-time alerts sent directly to your browser or device.</p>

                        <div class="mt-4">
                            <div class="flex items-center justify-between rounded-lg bg-gray-50 dark:bg-gray-700/50 px-4 py-3.5">
                                <div>
                                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">Desktop Browser Notifications</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Enable instant pop-up alerts for urgent approvals and critical errors.</p>
                                </div>
                                <button type="button" data-toggle class="toggle-switch relative inline-flex h-6 w-11 items-center rounded-full bg-pln-700 transition-colors shrink-0 ml-4">
                                    <span class="toggle-dot inline-block h-4 w-4 transform rounded-full bg-white translate-x-6 transition-transform"></span>
                                </button>
                            </div>
                        </div>

                        <div class="mt-8 flex items-center justify-end gap-3">
                            <button type="button" class="px-4 py-2.5 rounded-md text-sm font-medium text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                                Batalkan Perubahan
                            </button>
                            <button type="button" class="px-4 py-2.5 rounded-md text-sm font-semibold text-white bg-pln-800 hover:bg-pln-700">
                                Simpan Pengaturan
                            </button>
                        </div>
                    </div>

                    {{-- Keamanan --}}
                    <div id="tab-keamanan" class="settings-panel hidden space-y-4">

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
                                    <input id="current_password" name="current_password" type="password"
                                        class="mt-1.5 w-full rounded-md border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-pln-700 focus:outline-none focus:ring-2 focus:ring-pln-700/20">
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="password" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Kata Sandi Baru</label>
                                        <input id="password" name="password" type="password"
                                            class="mt-1.5 w-full rounded-md border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-pln-700 focus:outline-none focus:ring-2 focus:ring-pln-700/20">
                                    </div>
                                    <div>
                                        <label for="password_confirmation" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Konfirmasi Kata Sandi Baru</label>
                                        <input id="password_confirmation" name="password_confirmation" type="password"
                                            class="mt-1.5 w-full rounded-md border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-pln-700 focus:outline-none focus:ring-2 focus:ring-pln-700/20">
                                    </div>
                                </div>

                                <div class="flex items-center justify-end gap-3 pt-2">
                                    <button type="reset" class="px-4 py-2.5 rounded-md text-sm font-medium text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        Batalkan Perubahan
                                    </button>
                                    <button type="submit" class="px-4 py-2.5 rounded-md text-sm font-semibold text-white bg-pln-800 hover:bg-pln-700">
                                        Simpan Pengaturan
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-accent-400 text-pln-800 flex items-center justify-center shrink-0">
                                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 2.25c-3.14 2.1-6.46 2.79-9 2.79v6.02c0 5.7 3.87 9.8 9 10.94 5.13-1.14 9-5.24 9-10.94V5.04c-2.54 0-5.86-.69-9-2.79Z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-bold text-gray-700 dark:text-gray-200">Otentikasi Dua Faktor (2FA)</h3>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Tambahkan lapisan keamanan ekstra pada akun Anda.</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/30 rounded-full px-2.5 py-1 shrink-0">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    Aktif
                                </span>
                            </div>

                            <div class="mt-4 flex items-center justify-between rounded-lg bg-gray-50 dark:bg-gray-700/50 px-4 py-3.5">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">Aplikasi Autentikator</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Google Authenticator aktif di iPhone 15 Pro.</p>
                                    </div>
                                </div>
                                <button type="button" class="px-3 py-1.5 rounded-md text-xs font-semibold text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600 hover:bg-white dark:hover:bg-gray-700 shrink-0">
                                    Atur Ulang
                                </button>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-pln-700 dark:text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
                                </svg>
                                <h3 class="text-sm font-bold text-gray-700 dark:text-gray-200">Sesi Aktif</h3>
                            </div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Riwayat login akun Anda di berbagai perangkat.</p>

                            <div class="mt-4 divide-y divide-gray-100 dark:divide-gray-700">
                                <div class="flex items-center justify-between py-3.5">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
                                        </svg>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">
                                                Chrome di Windows 11
                                                <span class="ml-1.5 inline-flex text-[10px] font-semibold text-cyan-700 dark:text-cyan-300 bg-cyan-50 dark:bg-cyan-900/30 rounded-full px-2 py-0.5 align-middle">SESI INI</span>
                                            </p>
                                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Jakarta, Indonesia • 192.168.1.65</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between py-3.5">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                                        </svg>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">Safari di iPhone 15 Pro</p>
                                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Bandung, Indonesia • 2 hari yang lalu</p>
                                        </div>
                                    </div>
                                    <button type="button" class="text-xs font-semibold text-red-600 dark:text-red-400 hover:text-red-700">
                                        Log Out
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <button type="button" class="px-4 py-2.5 rounded-md text-sm font-medium text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                                Batalkan Perubahan
                            </button>
                            <button type="button" class="px-4 py-2.5 rounded-md text-sm font-semibold text-white bg-pln-800 hover:bg-pln-700">
                                Simpan Pengaturan
                            </button>
                        </div>
                    </div>

                    {{-- Preferensi Sistem --}}
                    <div id="tab-preferensi" class="settings-panel hidden bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-pln-800 dark:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 0 1 0 .255c-.007.378.138.751.43.991l1.005.828c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 0 1 0-.255c.007-.378-.138-.751-.43-.991l-1.004-.828a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.281Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <h2 class="text-base font-bold text-pln-800 dark:text-white">Konfigurasi Tampilan & Wilayah</h2>
                        </div>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Sesuaikan bagaimana data dan antarmuka ditampilkan pada perangkat Anda.</p>

                        <hr class="my-6 border-gray-100 dark:border-gray-700">

                        <div>
                            <label for="language" class="block text-[11px] font-semibold tracking-wide text-gray-500 dark:text-gray-400 uppercase">
                                Language / Bahasa
                            </label>
                            <select id="language" name="language"
                                class="mt-1.5 w-full max-w-sm rounded-md border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-pln-700 focus:outline-none focus:ring-2 focus:ring-pln-700/20">
                                <option value="id" selected>Indonesia</option>
                                <option value="en">English</option>
                            </select>
                            <p class="mt-1.5 text-xs text-gray-400 dark:text-gray-500">Perubahan bahasa akan diterapkan ke seluruh modul navigasi.</p>
                        </div>

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
    // Admin dropdown toggle
    const adminMenuToggle = document.getElementById('adminMenuToggle');
    const adminSubmenu = document.getElementById('adminSubmenu');
    const adminMenuChevron = document.getElementById('adminMenuChevron');
    if (adminMenuToggle && adminSubmenu) {
        adminMenuToggle.addEventListener('click', () => {
            adminSubmenu.classList.toggle('hidden');
            adminMenuChevron.classList.toggle('rotate-180');
        });
    }

    // Settings tabs
    const tabButtons = document.querySelectorAll('.settings-tab');
    const panels = document.querySelectorAll('.settings-panel');

    tabButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            const target = btn.dataset.tab;

            tabButtons.forEach((b) => {
                b.classList.remove('bg-pln-800', 'text-white');
                b.classList.add('text-gray-600', 'dark:text-gray-300', 'hover:bg-gray-50', 'dark:hover:bg-gray-700');
            });
            btn.classList.add('bg-pln-800', 'text-white');
            btn.classList.remove('text-gray-600', 'dark:text-gray-300', 'hover:bg-gray-50', 'dark:hover:bg-gray-700');

            panels.forEach((panel) => {
                panel.classList.toggle('hidden', panel.id !== `tab-${target}`);
            });
        });
    });

    // Notification toggle switches
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

    // ===== DARK MODE (baru apply pas klik "Simpan Pengaturan") =====
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