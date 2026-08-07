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
            $assetActive = request()->routeIs('manage-asset*') || request()->routeIs('manage-tower*') || request()->routeIs('manage-access-point*') || request()->routeIs('manage-router*') || request()->routeIs('manage-switch*');
            $adminActive = request()->routeIs('manage-user*') || request()->routeIs('manage-unit*');
        @endphp

        {{-- Manage Asset dropdown --}}
        <div>
            <button type="button" id="assetMenuToggle"
                class="w-full flex items-center justify-between px-3 py-2.5 rounded-md text-sm font-medium {{ $assetActive ? 'text-white bg-white/5' : 'text-pln-100 hover:bg-white/5' }}">
                <span class="flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M3.75 9v.75c0 .414.336.75.75.75h15a.75.75 0 0 0 .75-.75V9m-16.5 0-1.5-4.5h18l-1.5 4.5m-16.5 0h16.5" />
                    </svg>
                    Manage Asset
                </span>
                <svg id="assetMenuChevron" class="w-3.5 h-3.5 transition-transform duration-200 {{ $assetActive ? 'rotate-180' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </button>
            <div id="assetSubmenu" class="{{ $assetActive ? '' : 'hidden' }} ml-7 mt-1 border-l border-white/10 pl-3 space-y-1">
                <a href="{{ route('manage-tower') }}"
                   class="relative block px-2 py-2 text-sm {{ request()->routeIs('manage-tower*') || request()->routeIs('manage-asset*') ? 'text-white font-medium before:absolute before:-left-[13px] before:top-1/2 before:-translate-y-1/2 before:w-1 before:h-5 before:bg-accent-400 before:rounded-r' : 'text-pln-100 hover:text-white' }}">
                    Tower
                </a>
                <a href="{{ route('manage-access-point') }}"
                   class="relative block px-2 py-2 text-sm {{ request()->routeIs('manage-access-point*') ? 'text-white font-medium before:absolute before:-left-[13px] before:top-1/2 before:-translate-y-1/2 before:w-1 before:h-5 before:bg-accent-400 before:rounded-r' : 'text-pln-100 hover:text-white' }}">
                    Access Point
                </a>
                <a href="{{ route('manage-router') }}"
                   class="relative block px-2 py-2 text-sm {{ request()->routeIs('manage-router*') ? 'text-white font-medium before:absolute before:-left-[13px] before:top-1/2 before:-translate-y-1/2 before:w-1 before:h-5 before:bg-accent-400 before:rounded-r' : 'text-pln-100 hover:text-white' }}">
                    Router
                </a>
                <a href="{{ route('manage-switch') }}"
                   class="relative block px-2 py-2 text-sm {{ request()->routeIs('manage-switch*') ? 'text-white font-medium before:absolute before:-left-[13px] before:top-1/2 before:-translate-y-1/2 before:w-1 before:h-5 before:bg-accent-400 before:rounded-r' : 'text-pln-100 hover:text-white' }}">
                    Switch
                </a>
            </div>
        </div>

        <a href="{{ route('manage-asset.history') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium {{ request()->routeIs('manage-asset.history') ? 'bg-white/10 text-white' : 'text-pln-100 hover:bg-white/5' }}">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <circle cx="12" cy="12" r="9" stroke-linecap="round" stroke-linejoin="round" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5V12l3 1.5" />
            </svg>
            Riwayat Perubahan
        </a>

        {{-- Admin dropdown --}}
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
            </div>
        </div>

        <a href="{{ route('settings') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium {{ request()->routeIs('settings*') ? 'bg-white/10 text-white' : 'text-pln-100 hover:bg-white/5' }}">
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

<script>
    const assetMenuToggle = document.getElementById('assetMenuToggle');
    const assetSubmenu = document.getElementById('assetSubmenu');
    const assetMenuChevron = document.getElementById('assetMenuChevron');
    if (assetMenuToggle && assetSubmenu) {
        assetMenuToggle.addEventListener('click', () => {
            assetSubmenu.classList.toggle('hidden');
            assetMenuChevron.classList.toggle('rotate-180');
        });
    }

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