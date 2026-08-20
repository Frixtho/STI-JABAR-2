@extends('layouts.app', [
    'title' => (($user ?? false) ? 'Edit Pengguna' : 'Tambah Pengguna Baru') . ' — PLN Asset Management'
])

@section('content')
<main class="p-6 lg:p-10 space-y-5 w-full">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-sm">
        <a href="{{ route('manage-user') }}" class="text-gray-400 dark:text-gray-500 hover:text-[#004A54] dark:hover:text-accent-400 transition-colors">
            Manage User
        </a>
        <svg class="w-3.5 h-3.5 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
        </svg>
        <span class="font-semibold text-gray-700 dark:text-gray-200">
            {{ ($user ?? false) ? 'Edit Pengguna' : 'Tambah Pengguna' }}
        </span>
    </nav>

    {{-- Title Header --}}
    <div>
        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
            Account Management
        </p>
        <h1 class="text-2xl font-bold text-pln-800 dark:text-white">
            {{ ($user ?? false) ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}
        </h1>
        <p class="mt-1 text-sm text-gray-400 dark:text-gray-500">
            {{ ($user ?? false) ? 'Perbarui informasi profil, akses, dan kata sandi pengguna.' : 'Tambahkan pengguna baru dan tentukan hak akses (role) ke dalam sistem.' }}
        </p>
    </div>

    {{-- Error Alert --}}
    @if ($errors->any())
        <div class="rounded-md border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/30 px-4 py-3 text-sm text-red-700 dark:text-red-400">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM --}}
    <form method="POST" action="{{ ($user ?? false) ? route('manage-user.update', $user->id) : route('manage-user.store') }}" 
          class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        @csrf
        @if ($user ?? false)
            @method('PATCH')
        @endif

        {{-- Card Header --}}
        <div class="flex items-start gap-4 p-8 pb-6">
            <div class="w-11 h-11 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
            </div>
            <div>
                <h2 class="text-base font-bold text-pln-800 dark:text-white">Informasi Akun</h2>
                <p class="text-sm text-gray-400 dark:text-gray-500">Lengkapi detail profil, akses, dan identitas pengguna di bawah ini.</p>
            </div>
        </div>

        {{-- FIELDS CONTAINER --}}
        <div class="border-t border-gray-100 dark:border-gray-700 p-8 space-y-6">

            {{-- BAGIAN 1: PROFIL & KREDENSIAL --}}
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-4">
                    Profil & Kredensial
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Nama Lengkap --}}
                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required placeholder="Contoh: Budi Santoso"
                               class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white placeholder-gray-400 focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                    </div>

                    {{-- Alamat Email --}}
                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Alamat Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required placeholder="Contoh: budi@pln.co.id"
                               class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white placeholder-gray-400 focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                    </div>

                    {{-- Kata Sandi --}}
                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            Kata Sandi (Password) {!! ($user ?? false) ? '' : '<span class="text-red-500">*</span>' !!}
                        </label>
                        <input type="password" name="password" {{ ($user ?? false) ? '' : 'required' }} minlength="8" placeholder="{{ ($user ?? false) ? 'Kosongkan jika tidak diubah' : 'Minimal 8 karakter' }}"
                               class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white placeholder-gray-400 focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                    </div>
                </div>
            </div>

            <hr class="border-gray-200 dark:border-gray-700">

            {{-- BAGIAN 2: IDENTITAS PEGAWAI & AKSES --}}
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-4">
                    Identitas & Hak Akses
                </h3>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- NIP --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Nomor Induk Pegawai (NIP) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nip" value="{{ old('nip', $user->nip ?? '') }}" required placeholder="Contoh: 19920815XXXX"
                                   class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white placeholder-gray-400 focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                        </div>

                        {{-- Departemen / Unit (Dinamis dari Database Units) --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Departemen / Unit <span class="text-red-500">*</span>
                            </label>
                            <select name="department" required class="...">
                                <option value="" disabled selected>Pilih Departemen / Unit</option>
                                @foreach(\App\Models\Unit::orderBy('name')->get() as $unit)
                                    <option value="{{ $unit->name }}" @selected(old('department', $user->department ?? '') == $unit->name)>
                                        {{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- ROLE / PERAN (DIUBAH MENJADI STI & IT SUPPORT) --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Peran / Hak Akses <span class="text-red-500">*</span>
                            </label>
                            <select name="role" required
                                    class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2.5 px-3 text-sm text-gray-800 dark:text-white focus:border-[#004A54] focus:outline-none focus:ring-1 focus:ring-[#004A54]">
                                <option value="" disabled {{ old('role', $user->role ?? '') ? '' : 'selected' }}>Pilih Peran</option>
                                <option value="STI" @selected(old('role', $user->role ?? '') === 'STI')>STI</option>
                                <option value="IT Support" @selected(old('role', $user->role ?? '') === 'IT Support')>IT Support</option>
                            </select>
                        </div>
                    </div>

                    {{-- Status Akun --}}
                    <div>
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300 block mb-2">
                            Status Akun <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center gap-6">
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="status" value="Aktif" {{ old('status', $user->status ?? 'Aktif') == 'Aktif' ? 'checked' : '' }}
                                       class="w-4 h-4 text-[#004A54] focus:ring-[#004A54] cursor-pointer">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-[#004A54] transition-colors">Aktif</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="status" value="Non-Aktif" {{ old('status', $user->status ?? '') == 'Non-Aktif' ? 'checked' : '' }}
                                       class="w-4 h-4 text-red-600 focus:ring-red-600 cursor-pointer">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-red-600 transition-colors">Non-Aktif</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- INFO BANNER --}}
            <div class="flex items-start gap-3 rounded-lg bg-cyan-50 dark:bg-cyan-900/20 border border-cyan-100 dark:border-cyan-900/40 px-4 py-3 mt-6">
                <svg class="w-4 h-4 text-cyan-600 dark:text-cyan-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="12" r="9" stroke-linecap="round" stroke-linejoin="round" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8h.01M11 12h1v4h1" />
                </svg>
                <p class="text-xs text-cyan-800 dark:text-cyan-300">
                    {{ ($user ?? false) ? 'Perubahan profil akan segera diterapkan. Harap informasikan kepada pengguna bersangkutan jika terdapat perubahan kredensial login.' : 'Pastikan email dan password dicatat untuk diinformasikan kepada pemilik akun agar dapat mengakses sistem.' }}
                </p>
            </div>

        </div>

        {{-- BUTTONS --}}
        <div class="flex justify-end gap-3 px-8 py-5 border-t border-gray-100 dark:border-gray-700">
            <a href="{{ route('manage-user') }}" class="border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-6 py-2.5 rounded-md text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                Batal
            </a>
            <button type="submit" class="inline-flex items-center gap-2 bg-[#004A54] text-white px-6 py-2.5 rounded-md text-sm font-medium shadow-sm hover:bg-[#00363d] transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.25h13.5v13.5H5.25z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 5.25v4.5h7.5v-4.5" />
                </svg>
                {{ ($user ?? false) ? 'Simpan Perubahan' : 'Simpan Pengguna' }}
            </button>
        </div>

    </form>
</main>
@endsection