@extends('layouts.app', ['title' => 'Manage Switch — PLN Financial'])

@section('content')

<main class="p-6 lg:p-10 space-y-5 w-full">

    {{-- BREADCRUMB --}}
    <nav class="flex items-center gap-1.5 text-sm">

        <a href="{{ route('dashboard') }}"
           class="text-gray-400 hover:text-[#004A54]">
            Dashboard
        </a>

        <svg class="w-3.5 h-3.5 text-gray-300"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor"
             stroke-width="2">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  d="m8.25 4.5 7.5 7.5-7.5 7.5" />
        </svg>

        <span class="font-semibold text-gray-700">
            Manage Asset: Switch
        </span>

    </nav>


    {{-- HEADER --}}
    <div class="flex items-center justify-between">

        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                Manage Asset
            </p>

            <h1 class="text-2xl font-bold text-[#004A54]">
                Switch
            </h1>
        </div>

        <div class="flex items-center gap-3">

            <button
                type="button"
                class="border border-gray-300
                       text-gray-600
                       px-4 py-2.5 rounded-md
                       text-sm
                       hover:bg-gray-50"
            >
                ↑ Import Data
            </button>

            <a
                href="{{ route('manage-switch.create') }}"
                class="inline-flex items-center gap-2
                       bg-[#004A54] text-white
                       px-4 py-2.5 rounded-md
                       text-sm font-medium
                       hover:bg-[#00363d]"
            >
                <span class="text-lg leading-none">+</span>
                Tambah Switch
            </a>

        </div>

    </div>


    {{-- FLASH --}}
    @if(session('success'))
        <div class="rounded-md border border-green-200
                    bg-green-50 px-4 py-3
                    text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-md border border-red-200
                    bg-red-50 px-4 py-3
                    text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif


    {{-- SEARCH --}}
    <form
        method="GET"
        action="{{ route('manage-switch') }}"
        class="bg-white rounded-xl
               border border-gray-200
               shadow-sm p-3"
    >

        <div class="flex items-center gap-3">

            <div class="relative flex-1">

                <svg
                    class="absolute left-3 top-1/2
                           -translate-y-1/2
                           w-4 h-4 text-gray-400"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.35-4.35"/>
                </svg>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari ID, merk, model, SN, IP, lokasi..."
                    class="w-full rounded-md
                           border border-gray-300
                           pl-10 pr-4 py-2.5
                           text-sm
                           focus:border-[#004A54]
                           focus:outline-none
                           focus:ring-1 focus:ring-[#004A54]"
                >

            </div>

            <button
                type="submit"
                class="px-5 py-2.5
                       rounded-md
                       bg-[#004A54]
                       text-white
                       text-sm font-medium
                       hover:bg-[#00363d]"
            >
                Cari
            </button>

            <a
                href="{{ route('manage-switch') }}"
                class="px-5 py-2.5
                       rounded-md
                       border border-[#004A54]
                       text-[#004A54]
                       text-sm font-medium
                       hover:bg-gray-50"
            >
                Reset
            </a>

        </div>

    </form>


    {{-- TABLE --}}
    <div class="bg-white rounded-xl
                border border-gray-200
                shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-50 border-b border-gray-200">

                    <tr class="text-left">

                        <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase">
                            ID Aset
                        </th>

                        <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase">
                            Merk / Model
                        </th>

                        <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase">
                            Serial Number
                        </th>

                        <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase">
                            IP / MAC
                        </th>

                        <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase">
                            Tipe
                        </th>

                        <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase">
                            Lokasi
                        </th>

                        <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase">
                            Kondisi
                        </th>

                        <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase">
                            Ops
                        </th>

                        <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase">
                            Kritikalitas
                        </th>

                        <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase">
                            Garansi
                        </th>

                        <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase text-right">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @forelse($switches as $switch)

                        <tr class="hover:bg-gray-50 transition-colors">

                            <td class="px-5 py-4">
                                <span class="font-semibold text-[#004A54]">
                                    {{ $switch->id_aset }}
                                </span>
                            </td>


                            <td class="px-5 py-4">

                                <div class="font-medium text-gray-800">
                                    {{ $switch->merk }}
                                </div>

                                <div class="text-xs text-gray-400 mt-0.5">
                                    {{ $switch->model }}
                                </div>

                            </td>


                            <td class="px-5 py-4 text-gray-700">
                                {{ $switch->serial_number }}
                            </td>


                            <td class="px-5 py-4">

                                <div class="text-gray-700">
                                    {{ $switch->ip_address ?? '-' }}
                                </div>

                                <div class="text-xs text-gray-400 mt-0.5">
                                    {{ $switch->mac_address ?? '-' }}
                                </div>

                            </td>


                            <td class="px-5 py-4 text-xs text-gray-600">
                                {{ $switch->tipe_switch ?? '-' }}
                            </td>


                            <td class="px-5 py-4 text-gray-700">
                                {{ $switch->lokasi_aset_saat_ini }}
                            </td>


                            <td class="px-5 py-4">

                                @php
                                    $kondisi = strtolower($switch->status_kondisi ?? '');
                                @endphp

                                <span class="inline-flex items-center
                                    px-2.5 py-1 rounded-full
                                    text-xs font-medium
                                    {{ $kondisi === 'baik'
                                        ? 'bg-green-100 text-green-700'
                                        : ($kondisi === 'rusak'
                                            ? 'bg-red-100 text-red-700'
                                            : 'bg-yellow-100 text-yellow-700') }}">

                                    {{ $switch->status_kondisi }}

                                </span>

                            </td>


                            <td class="px-5 py-4">

                                @php
                                    $ops = strtolower($switch->status_operasional ?? '');
                                @endphp

                                <span class="inline-flex items-center
                                    px-2.5 py-1 rounded-full
                                    text-xs font-medium
                                    {{ $ops === 'aktif'
                                        ? 'bg-blue-100 text-blue-700'
                                        : 'bg-gray-100 text-gray-600' }}">

                                    {{ $switch->status_operasional }}

                                </span>

                            </td>


                            <td class="px-5 py-4">

                                @php
                                    $kritikalitas = strtolower($switch->tingkat_kritikalitas ?? '');
                                @endphp

                                <span class="inline-flex items-center
                                    px-2.5 py-1 rounded-full
                                    text-xs font-medium
                                    {{ $kritikalitas === 'kritis'
                                        ? 'bg-red-100 text-red-700'
                                        : ($kritikalitas === 'penting'
                                            ? 'bg-yellow-100 text-yellow-700'
                                            : 'bg-gray-100 text-gray-600') }}">

                                    {{ $switch->tingkat_kritikalitas }}

                                </span>

                            </td>


                            <td class="px-5 py-4 text-xs text-gray-600">

                                @if($switch->masa_berlaku_garansi)
                                    {{ $switch->masa_berlaku_garansi->format('d/m/Y') }}
                                @else
                                    Tidak dicatat
                                @endif

                            </td>


                            <td class="px-5 py-4">

                                <div class="flex items-center justify-end gap-2">

                                    <a
                                        href="{{ route('manage-switch.edit', $switch->id) }}"
                                        title="Edit"
                                        class="w-9 h-9
                                               flex items-center justify-center
                                               rounded-md
                                               border border-gray-200
                                               text-gray-500
                                               hover:text-[#004A54]
                                               hover:border-[#004A54]"
                                    >
                                        <svg
                                            class="w-4 h-4"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                                            />
                                        </svg>
                                    </a>


                                    <form
                                        action="{{ route('manage-switch.destroy', $switch->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Hapus switch {{ $switch->id_aset }}?')"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            title="Hapus"
                                            class="w-9 h-9
                                                   flex items-center justify-center
                                                   rounded-md
                                                   border border-gray-200
                                                   text-gray-500
                                                   hover:text-red-600
                                                   hover:border-red-300"
                                        >

                                            <svg
                                                class="w-4 h-4"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7"
                                                />

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M5 7h14M10 11v6M14 11v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"
                                                />
                                            </svg>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="11"
                                class="px-5 py-16 text-center">

                                <div class="text-gray-400">

                                    <svg
                                        class="w-10 h-10 mx-auto mb-3"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77-1.333.192 3 1.732 3z"
                                        />
                                    </svg>

                                    <p>
                                        Belum ada data untuk kategori
                                        <strong>Switch</strong>.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        <div class="flex items-center justify-between
                    px-5 py-4
                    border-t border-gray-100
                    text-sm text-gray-500">

            <div>
                Menampilkan
                {{ $switches->firstItem() ?? 0 }}
                -
                {{ $switches->lastItem() ?? 0 }}
                dari
                {{ $switches->total() }}
                data
            </div>

            <div>
                {{ $switches->onEachSide(1)->links() }}
            </div>

        </div>

    </div>

</main>

@endsection