@extends('layouts.app', ['title' => 'Manage User — PLN Asset Management'])

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
        <main class="p-6 space-y-5">

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

            {{-- Header Title & Action Button --}}
            <div class="flex items-center justify-between">
                <h1 class="text-lg font-bold text-pln-800 dark:text-white tracking-wide">Manage User</h1>
                
                <div class="flex items-center gap-3">
                    {{-- TOMBOL IMPORT YANG MENGARAH KE HALAMAN BARU --}}
                    <a href="{{ route('manage-user.import.form') }}" class="inline-flex items-center justify-center bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                        </svg>
                        Import File CSV/Excel
                    </a>

                    <a href="{{ route('manage-user.create') }}" class="inline-flex items-center justify-center bg-[#004A54] text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-[#00363d] transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add New User
                    </a>
                </div>
            </div>

           {{-- Filter & Cari --}}
            <div class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4 shadow-sm">
                <form action="{{ route('manage-user') }}" method="GET" class="w-full flex flex-row flex-wrap items-center gap-4">

                    <div class="flex-1 min-w-[200px] relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h12M4 18h8" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 pl-9 pr-4 text-sm text-gray-700 dark:text-gray-200 placeholder-gray-400 focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54] transition-colors">
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 whitespace-nowrap">Role:</label>
                        <div class="relative w-28">
                            <select name="role" onchange="this.form.submit()" class="w-full appearance-none rounded-lg border border-gray-300 dark:border-gray-600 py-2 pl-3 pr-7 text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54] cursor-pointer">
                                <option value="All" {{ request('role') == 'All' || !request('role') ? 'selected' : '' }}>All</option>
                                <option value="Admin" {{ request('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                                <option value="Manager" {{ request('role') == 'Manager' ? 'selected' : '' }}>Manager</option>
                                <option value="Staff" {{ request('role') == 'Staff' ? 'selected' : '' }}>Staff</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-gray-400">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 whitespace-nowrap">Status:</label>
                        <div class="relative w-28">
                            <select name="status" onchange="this.form.submit()" class="w-full appearance-none rounded-lg border border-gray-300 dark:border-gray-600 py-2 pl-3 pr-7 text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54] cursor-pointer">
                                <option value="All" {{ request('status') == 'All' || !request('status') ? 'selected' : '' }}>All</option>
                                <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Non-Aktif" {{ request('status') == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-gray-400">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Cari tambahan (Opsional agar user bisa tekan Enter / klik cari) --}}
                    <div class="shrink-0">
                        <button type="submit" class="bg-[#004A54] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-[#00363d] transition-colors">
                            Cari
                        </button>
                    </div>

                    <div class="shrink-0">
                        <a href="{{ route('manage-user') }}" class="border-2 border-[#004A54] dark:border-accent-400 text-[#004A54] dark:text-accent-400 px-5 py-2 rounded-lg text-sm font-bold hover:bg-cyan-50/50 dark:hover:bg-gray-700 transition-all block text-center tracking-wide">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- ===================== TABLE ===================== --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">

                <div class="grid grid-cols-5 bg-gray-50 dark:bg-gray-900/40 px-6 py-3 border-b border-gray-200 dark:border-gray-700 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    <div>User</div>
                    <div>Email</div>
                    <div>Role</div>
                    <div>Status</div>
                    <div class="text-center">Action</div>
                </div>

                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($users as $user)
                        <div class="grid grid-cols-5 items-center px-6 py-4 text-sm hover:bg-gray-50/50 dark:hover:bg-gray-700/40 transition-colors">

                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold text-xs flex items-center justify-center shrink-0 uppercase border border-gray-200 dark:border-gray-600">
                                    {{ implode('', array_map(function($v) { return $v ? $v[0] : ''; }, explode(' ', $user->name))) }}
                                </div>
                                <div class="leading-tight min-w-0">
                                    <p class="font-semibold text-[#004A54] dark:text-accent-400 truncate">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 truncate">ID: {{ $user->nip ?? '102022300029' }}</p>
                                </div>
                            </div>

                            <div class="text-gray-600 dark:text-gray-300 truncate pr-4">
                                {{ $user->email }}
                            </div>

                            <div>
                                @if(strcasecmp($user->role, 'Admin') === 0)
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-md bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-300 uppercase tracking-wide">Admin</span>
                                @elseif(strcasecmp($user->role, 'Manager') === 0)
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-md bg-purple-100 dark:bg-purple-900/40 text-purple-800 dark:text-purple-300 uppercase tracking-wide">Manager</span>
                                @else
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-md bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase tracking-wide">Staff</span>
                                @endif
                            </div>

                            <div>
                                @if(strcasecmp($user->status, 'Aktif') === 0)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-cyan-50 dark:bg-cyan-900/30 text-cyan-700 dark:text-cyan-300 border border-cyan-100 dark:border-cyan-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-500"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 border border-red-100 dark:border-red-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        Non-Aktif
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center justify-center gap-2">
                                @if(strcasecmp(auth()->user()->role, 'Admin') === 0)
                                    <a href="{{ route('manage-user.edit', $user->id) }}" class="text-gray-400 dark:text-gray-500 hover:text-[#004A54] dark:hover:text-accent-400 p-1.5 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>

                                    @if($user->email === 'admin@pln.co.id')
                                        <span class="text-gray-300 dark:text-gray-600 p-1.5 cursor-not-allowed" title="User bawaan sistem tidak dapat dihapus">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                        </span>
                                    @else
                                        <form action="{{ route('manage-user.destroy', $user->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna {{ $user->name }}?')"
                                            class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 dark:text-gray-500 hover:text-red-600 dark:hover:text-red-400 p-1.5 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150 focus:outline-none">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <span class="text-gray-300 dark:text-gray-600 text-xs italic">—</span>
                                @endif
                            </div>

                        </div>
                    @empty
                        <div class="p-12 text-center text-gray-400 dark:text-gray-500 bg-white dark:bg-gray-800">
                            <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            Belum ada data pengguna yang cocok ditemukan.
                        </div>
                    @endforelse
                </div>

                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                        Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} pengguna
                    </div>
                    <div class="laravel-pagination">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<script>
    // Hanya dideklarasikan satu kali
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