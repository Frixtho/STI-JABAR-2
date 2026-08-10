@extends('layouts.app', [
    'title' => ($switch ? 'Edit Switch' : 'Tambah Switch') . ' — PLN Financial'
])

@section('content')

<main class="p-6 lg:p-10 space-y-5 w-full">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-sm">
        <a href="{{ route('manage-switch') }}"
           class="text-gray-400 dark:text-gray-500 hover:text-[#004A54] dark:hover:text-accent-400 transition-colors">
            Switch
        </a>

        <svg class="w-3.5 h-3.5 text-gray-300 dark:text-gray-600"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor"
             stroke-width="2">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  d="m8.25 4.5 7.5 7.5-7.5 7.5" />
        </svg>

        <span class="font-semibold text-gray-700 dark:text-gray-200">
            {{ $switch ? 'Edit Switch' : 'Tambah Switch' }}
        </span>
    </nav>


    {{-- Title --}}
    <div>
        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
            Manage Asset
        </p>

        <h1 class="text-2xl font-bold text-pln-800 dark:text-white">
            {{ $switch ? 'Edit Switch' : 'Tambah Switch' }}
        </h1>

        <p class="mt-1 text-sm text-gray-400 dark:text-gray-500">
            {{ $switch
                ? 'Perbarui informasi Switch.'
                : 'Tambahkan informasi Switch baru.'
            }}
        </p>
    </div>


    {{-- Error --}}
    @if ($errors->any())
        <div class="rounded-md border border-red-200 dark:border-red-800
                    bg-red-50 dark:bg-red-900/30 px-4 py-3
                    text-sm text-red-700 dark:text-red-400">

            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif


    {{-- FORM --}}
    <form
        method="POST"
        action="{{ $switch
            ? route('manage-switch.update', $switch->id)
            : route('manage-switch.store')
        }}"
        class="bg-white dark:bg-gray-800 rounded-xl
               border border-gray-200 dark:border-gray-700
               shadow-sm"
    >

        @csrf

        @if ($switch)
            @method('PATCH')
        @endif


        {{-- Card Header --}}
        <div class="flex items-start gap-4 p-8 pb-6">

            <div class="w-11 h-11 rounded-lg bg-gray-100 dark:bg-gray-700
                        flex items-center justify-center shrink-0">

                <svg class="w-5 h-5 text-gray-600 dark:text-gray-300"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M4.5 6.75h15m-15 0A2.25 2.25 0 0 1 6.75 4.5h10.5a2.25 2.25 0 0 1 2.25 2.25m-15 0v10.5A2.25 2.25 0 0 0 6.75 19.5h10.5a2.25 2.25 0 0 0 2.25-2.25V6.75" />

                </svg>

            </div>

            <div>
                <h2 class="text-base font-bold text-pln-800 dark:text-white">
                    Informasi Switch
                </h2>

                <p class="text-sm text-gray-400 dark:text-gray-500">
                    Lengkapi informasi Switch sesuai data yang ditampilkan pada tabel Manage Asset.
                </p>
            </div>

        </div>


        {{-- FIELDS --}}
        <div class="border-t border-gray-100 dark:border-gray-700 p-8 space-y-6">


            {{-- ROW 1 --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- ID ASET --}}
                <div>
                    <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                        ID Aset <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="id_aset"
                        value="{{ old('id_aset', $switch->id_aset ?? '') }}"
                        required
                        placeholder="Contoh: SWT-001"
                        class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                               text-gray-800 dark:text-white placeholder-gray-400
                               focus:border-[#004A54] focus:outline-none
                               focus:ring-1 focus:ring-[#004A54]"
                    >
                </div>


                {{-- MERK --}}
                <div>
                    <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                        Merk <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="merk"
                        value="{{ old('merk', $switch->merk ?? '') }}"
                        required
                        placeholder="Contoh: Cisco"
                        class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                               text-gray-800 dark:text-white placeholder-gray-400
                               focus:border-[#004A54] focus:outline-none
                               focus:ring-1 focus:ring-[#004A54]"
                    >
                </div>


                {{-- MODEL --}}
                <div>
                    <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                        Model <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="model"
                        value="{{ old('model', $switch->model ?? '') }}"
                        required
                        placeholder="Contoh: Cisco Catalyst 2960"
                        class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                               text-gray-800 dark:text-white placeholder-gray-400
                               focus:border-[#004A54] focus:outline-none
                               focus:ring-1 focus:ring-[#004A54]"
                    >
                </div>

            </div>


            {{-- ROW 2 --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- SERIAL --}}
                <div>
                    <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                        Serial Number <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="serial_number"
                        value="{{ old('serial_number', $switch->serial_number ?? '') }}"
                        required
                        placeholder="Serial number"
                        class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                               text-gray-800 dark:text-white placeholder-gray-400
                               focus:border-[#004A54] focus:outline-none
                               focus:ring-1 focus:ring-[#004A54]"
                    >
                </div>


                {{-- IP --}}
                <div>
                    <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                        IP Address
                    </label>

                    <input
                        type="text"
                        name="ip_address"
                        value="{{ old('ip_address', $switch->ip_address ?? '') }}"
                        placeholder="Contoh: 192.168.1.1"
                        class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                               text-gray-800 dark:text-white placeholder-gray-400
                               focus:border-[#004A54] focus:outline-none
                               focus:ring-1 focus:ring-[#004A54]"
                    >
                </div>


                {{-- MAC --}}
                <div>
                    <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                        MAC Address
                    </label>

                    <input
                        type="text"
                        name="mac_address"
                        value="{{ old('mac_address', $switch->mac_address ?? '') }}"
                        placeholder="00:11:22:33:44:55"
                        class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                               text-gray-800 dark:text-white placeholder-gray-400
                               focus:border-[#004A54] focus:outline-none
                               focus:ring-1 focus:ring-[#004A54]"
                    >
                </div>

            </div>


            {{-- ROW 3 --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- TIPE SWITCH --}}
                <div>
                    <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                        Tipe Switch
                    </label>

                    <select
                        name="tipe_switch"
                        class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                               text-gray-800 dark:text-white focus:border-[#004A54]
                               focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                    >
                        <option value="">— Pilih Tipe —</option>

                        <option value="managed"
                            @selected(old('tipe_switch', $switch->tipe_switch ?? '') == 'managed')>
                            Managed
                        </option>

                        <option value="unmanaged"
                            @selected(old('tipe_switch', $switch->tipe_switch ?? '') == 'unmanaged')>
                            Unmanaged
                        </option>
                    </select>
                </div>


                {{-- LOKASI --}}
                <div>
                    <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                        Lokasi <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="lokasi_aset_saat_ini"
                        value="{{ old('lokasi_aset_saat_ini', $switch->lokasi_aset_saat_ini ?? '') }}"
                        required
                        placeholder="Contoh: UPT Bandung"
                        class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                               text-gray-800 dark:text-white placeholder-gray-400
                               focus:border-[#004A54] focus:outline-none
                               focus:ring-1 focus:ring-[#004A54]"
                    >
                </div>


                {{-- KONDISI --}}
                <div>
                    <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                        Kondisi <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="status_kondisi"
                        required
                        class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                               text-gray-800 dark:text-white focus:border-[#004A54]
                               focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                    >
                        <option value="">— Pilih Kondisi —</option>

                        <option value="baik"
                            @selected(old('status_kondisi', $switch->status_kondisi ?? '') == 'baik')>
                            Baik
                        </option>

                        <option value="rusak"
                            @selected(old('status_kondisi', $switch->status_kondisi ?? '') == 'rusak')>
                            Rusak
                        </option>

                        <option value="perlu_perbaikan"
                            @selected(old('status_kondisi', $switch->status_kondisi ?? '') == 'perlu_perbaikan')>
                            Perlu Perbaikan
                        </option>
                    </select>
                </div>

            </div>


            {{-- ROW 4 --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- OPS --}}
                <div>
                    <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                        Status Operasional <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="status_operasional"
                        required
                        class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                               text-gray-800 dark:text-white focus:border-[#004A54]
                               focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                    >
                        <option value="">— Pilih Status —</option>

                        <option value="aktif"
                            @selected(old('status_operasional', $switch->status_operasional ?? '') == 'aktif')>
                            Aktif
                        </option>

                        <option value="tidak_aktif"
                            @selected(old('status_operasional', $switch->status_operasional ?? '') == 'tidak_aktif')>
                            Tidak Aktif
                        </option>
                    </select>
                </div>


                {{-- KRITIKALITAS --}}
                <div>
                    <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                        Tingkat Kritikalitas <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="tingkat_kritikalitas"
                        required
                        class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                               text-gray-800 dark:text-white focus:border-[#004A54]
                               focus:outline-none focus:ring-1 focus:ring-[#004A54]"
                    >
                        <option value="">— Pilih Kritikalitas —</option>

                        <option value="kritis"
                            @selected(old('tingkat_kritikalitas', $switch->tingkat_kritikalitas ?? '') == 'kritis')>
                            Kritis
                        </option>

                        <option value="penting"
                            @selected(old('tingkat_kritikalitas', $switch->tingkat_kritikalitas ?? '') == 'penting')>
                            Penting
                        </option>

                        <option value="normal"
                            @selected(old('tingkat_kritikalitas', $switch->tingkat_kritikalitas ?? '') == 'normal')>
                            Normal
                        </option>
                    </select>
                </div>


                {{-- GARANSI --}}
                <div>
                    <label class="text-xs font-bold text-gray-700 dark:text-gray-300">
                        Masa Berlaku Garansi
                    </label>

                    <input
                        type="date"
                        name="masa_berlaku_garansi"
                        value="{{ old('masa_berlaku_garansi', optional($switch->masa_berlaku_garansi ?? null)->format('Y-m-d')) }}"
                        class="mt-1.5 w-full rounded-md border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-700 py-2.5 px-3 text-sm
                               text-gray-800 dark:text-white
                               focus:border-[#004A54] focus:outline-none
                               focus:ring-1 focus:ring-[#004A54]"
                    >
                </div>

            </div>


            {{-- INFO --}}
            <div class="flex items-start gap-3 rounded-lg
                        bg-cyan-50 dark:bg-cyan-900/20
                        border border-cyan-100 dark:border-cyan-900/40
                        px-4 py-3">

                <svg class="w-4 h-4 text-cyan-600 dark:text-cyan-400 mt-0.5 shrink-0"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">

                    <circle cx="12" cy="12" r="9"
                            stroke-linecap="round"
                            stroke-linejoin="round" />

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 8h.01M11 12h1v4h1" />

                </svg>

                <p class="text-xs text-cyan-800 dark:text-cyan-300">
                    Data yang dimasukkan akan digunakan untuk menampilkan
                    informasi utama Switch pada tabel Manage Asset.
                </p>

            </div>

        </div>


        {{-- BUTTON --}}
        <div class="flex justify-end gap-3 px-8 py-5
                    border-t border-gray-100 dark:border-gray-700">

            <a
                href="{{ route('manage-switch') }}"
                class="border border-gray-300 dark:border-gray-600
                       text-gray-700 dark:text-gray-300
                       px-6 py-2.5 rounded-md text-sm
                       hover:bg-gray-50 dark:hover:bg-gray-700
                       transition-colors"
            >
                Batal
            </a>

            <button
                type="submit"
                class="inline-flex items-center gap-2
                       bg-[#004A54] text-white
                       px-6 py-2.5 rounded-md
                       text-sm font-medium
                       hover:bg-[#00363d]
                       transition-colors"
            >

                <svg class="w-4 h-4"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M5.25 5.25h13.5v13.5H5.25z" />

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M8.25 5.25v4.5h7.5v-4.5" />

                </svg>

                {{ $switch ? 'Simpan Perubahan' : 'Simpan Switch' }}

            </button>

        </div>

    </form>

</main>

@endsection