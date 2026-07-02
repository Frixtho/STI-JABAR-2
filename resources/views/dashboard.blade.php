@extends('layouts.guest', ['title' => 'Dashboard — PLN Financial'])

@section('content')
<div class="min-h-screen bg-gray-50">

    {{-- Top bar --}}
    <header class="bg-pln-800 text-white px-8 py-4 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-accent-400" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M13 2 3 14h7l-1 8 11-14h-7l1-6Z" />
            </svg>
            <div class="leading-tight">
                <p class="font-bold text-sm tracking-tight">PLN Financial</p>
                <p class="text-[10px] tracking-[0.2em] text-pln-100/80">UTILITY MANAGEMENT</p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm font-medium text-pln-100 hover:text-white">
                Log out
            </button>
        </form>
    </header>

    {{-- Content --}}
    <div class="max-w-3xl mx-auto px-6 py-16 text-center">
        <h1 class="text-2xl font-bold text-pln-800">
            Welcome, {{ auth()->user()->name }}
        </h1>
        <p class="mt-2 text-sm text-gray-500">
            You're logged in as <span class="font-medium">{{ auth()->user()->email }}</span>.
            This is a placeholder dashboard — replace it with your actual utility
            asset management views.
        </p>
    </div>
</div>
@endsection