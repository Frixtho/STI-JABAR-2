@extends('layouts.guest', ['title' => 'Secure Access — PLN Financial'])

@section('content')
<div class="min-h-screen w-full flex flex-col lg:flex-row bg-gray-50">

    {{-- ===================== LEFT: DECORATIVE PANEL ===================== --}}
    <div class="hidden lg:flex lg:w-[55%] relative server-panel flex-col justify-between p-14">

        {{-- Logo --}}
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-accent-400" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M13 2 3 14h7l-1 8 11-14h-7l1-6Z" />
            </svg>
            <div class="leading-tight">
                <p class="font-bold text-sm text-white tracking-tight">PLN Financial</p>
                <p class="text-[10px] tracking-[0.2em] text-pln-100/80">UTILITY MANAGEMENT</p>
            </div>
        </div>

        {{-- Headline --}}
        <div class="server-panel__content max-w-md">
            <h1 class="text-3xl font-bold text-white leading-snug">
                Empowering Infrastructure Through Fiscal Precision
            </h1>
            <div class="w-10 h-1 bg-accent-400 rounded-full mt-4 mb-4"></div>
            <p class="text-sm text-pln-100/80 leading-relaxed">
                Advanced utility asset management and financial auditing
                for modern infrastructure ecosystems. Reliability through
                data-driven governance.
            </p>
        </div>

        {{-- Footer badges --}}
        <div class="flex items-center gap-6 text-[11px] text-pln-100/70">
            <span class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 2.25c-3.14 2.1-6.46 2.79-9 2.79v6.02c0 5.7 3.87 9.8 9 10.94 5.13-1.14 9-5.24 9-10.94V5.04c-2.54 0-5.86-.69-9-2.79Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4" />
                </svg>
                ISO 27001 Certified
            </span>
            <span class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 0h10.5a1.5 1.5 0 0 1 1.5 1.5v7.5a1.5 1.5 0 0 1-1.5 1.5H6.75a1.5 1.5 0 0 1-1.5-1.5v-7.5a1.5 1.5 0 0 1 1.5-1.5Z" />
                </svg>
                Bank-Grade Encryption
            </span>
        </div>
    </div>

    {{-- ===================== RIGHT: LOGIN CARD ===================== --}}
    <div class="w-full lg:w-[45%] flex items-center justify-center px-6 py-10">
        <div class="w-full max-w-sm bg-white rounded-xl shadow-sm border border-gray-100 p-8">

            <h2 class="text-xl font-bold text-pln-800">Secure Access</h2>
            <p class="mt-1.5 text-sm text-gray-500">
                Enter your credentials to manage utility assets and
                financial statements.
            </p>

            @if ($errors->any())
                <div class="mt-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('status'))
                <div class="mt-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
                @csrf

                {{-- ID / Email --}}
                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-700">
                        ID Transaksi / Email
                    </label>
                    <div class="mt-1.5 relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0" />
                            </svg>
                        </span>
                        <input id="email" name="email" type="text" value="{{ old('email') }}" required autofocus
                            placeholder="Admin ID or registered email"
                            class="w-full rounded-md border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-3 text-sm text-gray-800 placeholder-gray-400 focus:border-pln-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-pln-700/20">
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-xs font-semibold text-gray-700">
                            Password
                        </label>
                        <a href="#" class="text-xs font-medium text-pln-700 hover:text-pln-800">
                            Forgot Password?
                        </a>
                    </div>
                    <div class="mt-1.5 relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 0h10.5a1.5 1.5 0 0 1 1.5 1.5v7.5a1.5 1.5 0 0 1-1.5 1.5H6.75a1.5 1.5 0 0 1-1.5-1.5v-7.5a1.5 1.5 0 0 1 1.5-1.5Z" />
                            </svg>
                        </span>
                        <input id="password" name="password" type="password" required
                            placeholder="••••••••"
                            class="w-full rounded-md border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-10 text-sm text-gray-800 placeholder-gray-400 focus:border-pln-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-pln-700/20">
                        <button type="button" id="togglePassword"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600" aria-label="Show password">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.644C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.437 0 .644C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Remember device --}}
                <div class="flex items-center gap-2 pt-1">
                    <input id="remember" name="remember" type="checkbox"
                        class="h-4 w-4 rounded border-gray-300 text-pln-700 focus:ring-pln-700/40">
                    <label for="remember" class="text-sm text-gray-600">
                        Remember this device for 30 days
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 rounded-md bg-pln-800 py-2.5 text-xs font-semibold uppercase tracking-wide text-white hover:bg-pln-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 2.25c-3.14 2.1-6.46 2.79-9 2.79v6.02c0 5.7 3.87 9.8 9 10.94 5.13-1.14 9-5.24 9-10.94V5.04c-2.54 0-5.86-.69-9-2.79Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4" />
                    </svg>
                    Secure Login
                </button>

                <p class="text-center text-sm text-gray-500 pt-1">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-pln-700 font-semibold hover:text-pln-800">Register Now</a>
                </p>
            </form>

            <hr class="my-6 border-gray-100">

            {{-- Footer --}}
            <div class="flex items-center justify-between text-[11px] text-gray-400">
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="9" stroke-linecap="round" stroke-linejoin="round" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5V12l3 1.5" />
                    </svg>
                    Support Terminal:
                    <a href="tel:1500756" class="text-pln-700 font-medium">1500-PLN</a>
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="9" stroke-linecap="round" stroke-linejoin="round" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18M12 3c2.5 2.7 2.5 15.3 0 18M12 3c-2.5 2.7-2.5 15.3 0 18" />
                    </svg>
                    ENGLISH (ID)
                </span>
            </div>
        </div>
    </div>
</div>

<script>
    const toggleBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    if (toggleBtn && passwordInput) {
        toggleBtn.addEventListener('click', () => {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
        });
    }
</script>
@endsection