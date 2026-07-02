@extends('layouts.guest', ['title' => 'Create Account — PLN Financial'])

@section('content')
<div class="min-h-screen w-full flex flex-col lg:flex-row">

    {{-- ===================== LEFT: FORM PANEL ===================== --}}
    <div class="w-full lg:w-1/2 flex flex-col">

        <header class="bg-pln-800 text-white px-8 py-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-accent-400" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M13 2 3 14h7l-1 8 11-14h-7l1-6Z" />
            </svg>
            <div class="leading-tight">
                <p class="font-bold text-sm tracking-tight">PLN Financial</p>
                <p class="text-[10px] tracking-[0.2em] text-pln-100/80">UTILITY MANAGEMENT</p>
            </div>
        </header>

        <div class="flex-1 flex items-center justify-center px-8 py-10">
            <div class="w-full max-w-sm">

                <h1 class="text-2xl font-bold text-pln-800">Create Account</h1>
                <p class="mt-2 text-sm text-gray-500">
                    Access the PLN financial infrastructure dashboard and manage
                    high-density assets with professional precision.
                </p>

                @if ($errors->any())
                    <div class="mt-5 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="block text-[11px] font-semibold tracking-wide text-gray-500 uppercase">
                            Full Name
                        </label>
                        <div class="mt-1.5 relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0" />
                                </svg>
                            </span>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                                placeholder="Johnathan Doe"
                                class="w-full rounded-md border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-3 text-sm text-gray-800 placeholder-gray-400 focus:border-pln-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-pln-700/20">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-[11px] font-semibold tracking-wide text-gray-500 uppercase">
                            Corporate Email
                        </label>
                        <div class="mt-1.5 relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0-.83.67-1.5 1.5-1.5h16.5c.83 0 1.5.67 1.5 1.5v10.5a1.5 1.5 0 0 1-1.5 1.5H3.75a1.5 1.5 0 0 1-1.5-1.5V6.75Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m3 7 9 6 9-6" />
                                </svg>
                            </span>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required
                                placeholder="name@company.com"
                                class="w-full rounded-md border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-3 text-sm text-gray-800 placeholder-gray-400 focus:border-pln-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-pln-700/20">
                        </div>
                    </div>

                    <div>
                        <label for="phone" class="block text-[11px] font-semibold tracking-wide text-gray-500 uppercase">
                            Phone Number
                        </label>
                        <div class="mt-1.5 relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h1.5a1.5 1.5 0 0 0 1.5-1.5v-2.1a1.5 1.5 0 0 0-1.2-1.47l-3.34-.67a1.5 1.5 0 0 0-1.55.6l-.6.9a11.25 11.25 0 0 1-5.28-5.28l.9-.6a1.5 1.5 0 0 0 .6-1.55l-.67-3.34a1.5 1.5 0 0 0-1.47-1.2H3.75a1.5 1.5 0 0 0-1.5 1.5Z" />
                                </svg>
                            </span>
                            <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required
                                placeholder="+1 (555) 000-0000"
                                class="w-full rounded-md border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-3 text-sm text-gray-800 placeholder-gray-400 focus:border-pln-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-pln-700/20">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-[11px] font-semibold tracking-wide text-gray-500 uppercase">
                            Secure Password
                        </label>
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
                        <input type="hidden" name="password_confirmation" id="password_confirmation_mirror">
                    </div>

                    <div class="flex items-start gap-2 pt-1">
                        <input id="terms" name="terms" type="checkbox" required
                            class="mt-0.5 h-4 w-4 rounded border-gray-300 text-pln-700 focus:ring-pln-700/40">
                        <label for="terms" class="text-sm text-gray-600">
                            I agree to the
                            <a href="#" class="text-pln-700 underline hover:text-pln-800">Terms of Service</a>
                            and
                            <a href="#" class="text-pln-700 underline hover:text-pln-800">Privacy Policy</a>.
                        </label>
                    </div>

                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 rounded-md bg-pln-800 py-2.5 text-sm font-semibold text-white hover:bg-pln-700 transition-colors">
                        Register
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </button>
                </form>

                <hr class="my-6 border-gray-200">

                <p class="text-center text-sm text-gray-500">
                    Already have a corporate account?
                    <a href="{{ route('login') }}" class="text-pln-700 font-medium hover:text-pln-800">Log in here</a>
                </p>
            </div>
        </div>
    </div>

    {{-- ===================== RIGHT: DECORATIVE PANEL ===================== --}}
    <div class="hidden lg:block lg:w-1/2 relative server-panel">
        <div class="server-panel__content h-full flex flex-col justify-end p-14">
            <h2 class="text-white text-3xl font-bold leading-snug max-w-md">
                Securing the future of utility financial infrastructure.
            </h2>
            <p class="mt-4 text-pln-100/80 text-sm max-w-sm leading-relaxed">
                PLN provides the precision tools required for large-scale financial
                monitoring and asset management across complex power and utility grids.
            </p>
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

    if (passwordInput) {
        passwordInput.addEventListener('input', () => {
            document.getElementById('password_confirmation_mirror').value = passwordInput.value;
        });
    }
</script>
@endsection