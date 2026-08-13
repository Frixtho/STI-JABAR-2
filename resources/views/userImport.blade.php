@extends('layouts.app', ['title' => 'Import Daftar Pengguna — PLN Financial'])

@section('content')
<div class="min-h-screen flex bg-gray-50 dark:bg-gray-900">
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
                    <span class="text-gray-400">Import Pengguna</span>
                </div>

                <h1 class="text-2xl font-bold text-[#063333] dark:text-white">Import Daftar Pengguna (User)</h1>

                @if(session('import_skipped_reasons'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg text-xs max-h-48 overflow-y-auto">
                        <p class="font-bold mb-1">Catatan / Error Baris Dilewati:</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach(array_unique(session('import_skipped_reasons')) as $reason)
                                <li>{{ $reason }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                {{-- Form Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-300">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-gray-800 dark:text-gray-100">Upload File Excel / CSV Daftar User</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Unggah file data dengan format kolom: No | Nama User | Keterangan.</p>
                        </div>
                    </div>

                    <form action="{{ route('manage-user.import.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                        @csrf
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300 tracking-wide">Pilih File CSV/Excel *</label>
                            <input type="file" name="file" accept=".csv,.txt" required
                                class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2 px-3 text-sm text-gray-800 dark:text-white file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:bg-[#004A54] file:text-white hover:file:bg-[#003840] cursor-pointer focus:border-[#064e57] focus:outline-none">
                            <p class="text-[10px] text-gray-400 mt-1">Sistem otomatis menggunakan role "Staff", password default "pln12345", dan email dummy berdasarkan nama.</p>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('manage-user') }}" class="border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-5 py-2 rounded-md text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                Batal
                            </a>
                            <button type="submit" class="bg-[#004A54] text-white px-5 py-2 rounded-md text-sm font-medium hover:bg-[#00363d] transition-colors shadow-sm">
                                Proses Import User
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</div>
@endsection