@extends('layouts.app', ['title' => 'Manage Server Storage — PLN Financial'])

@section('content')
<div class="min-h-screen flex bg-gray-50 dark:bg-gray-900">
    <div class="flex-1 min-w-0">

        {{-- Top Bar --}}
        <header class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 px-6 py-4 flex items-center justify-between gap-4">
            <div class="relative w-full max-w-sm">
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="7" stroke-linecap="round" stroke-linejoin="round" /><path stroke-linecap="round" stroke-linejoin="round" d="m20 20-3.5-3.5" /></svg>
                </span>
                <input type="text" placeholder="Cari data..." class="w-full rounded-md border border-gray-200 bg-gray-50 py-2 pl-10 pr-3 text-sm focus:border-pln-700 focus:outline-none focus:ring-2 focus:ring-pln-700/20">
            </div>

            <div class="flex items-center gap-5 shrink-0">
                <div class="flex items-center gap-2">
                    <div class="text-right leading-tight">
                        <p class="text-sm font-semibold text-pln-800">{{ auth()->user()->name ?? 'Admin PLN' }}</p>
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-accent-500">{{ auth()->user()->role ?? 'ADMIN' }}</p>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-pln-800 text-white flex items-center justify-center font-bold text-xs uppercase shrink-0">
                        {{ implode('', array_map(fn($w) => $w[0] ?? '', array_slice(explode(' ', auth()->user()->name ?? 'Admin PLN'), 0, 2))) }}
                    </div>
                </div>
            </div>
        </header>

        {{-- Main Content Section --}}
        <div class="px-6 space-y-4">
            <nav class="flex items-center gap-1.5 text-sm pt-4">
                <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-[#004A54]">Dashboard</a>
                <svg class="w-3.5 h-3.5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                <span class="font-semibold text-gray-700">Manage Asset: Server Storage</span>
            </nav>

            <div class="flex items-center justify-between flex-wrap gap-3">
                <div>
                    <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">MANAGE ASSET</p>
                    <h1 class="text-xl font-bold text-pln-800 tracking-wide">Daftar Asset Server Storage</h1>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('manage-server-storage.import.form') }}" class="inline-flex items-center gap-2 border border-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-50">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/></svg>
                        Import Data
                    </a>
                    <a href="{{ route('manage-server-storage.create') }}" class="inline-flex items-center gap-2 bg-[#004A54] text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-[#00363d]">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Tambah Storage
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mx-6 mt-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mx-6 mt-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
        @endif

        {{-- FILTER --}}
        <div class="mx-6 mt-4 bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
            <form method="GET" action="{{ route('manage-server-storage') }}" class="flex flex-row flex-wrap items-center gap-4">
                <div class="flex-1 min-w-[250px]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID, hostname, merk, model, SN..." class="w-full rounded-lg border border-gray-300 py-2.5 px-4 text-sm focus:border-[#004A54] focus:outline-none">
                </div>
                <div>
                    <select name="kondisi" onchange="this.form.submit()" class="rounded-lg border border-gray-300 py-2.5 px-3 text-sm">
                        <option value="">Semua kondisi</option>
                        <option value="baik" @selected(request('kondisi') == 'baik')>Baik</option>
                        <option value="rusak dapat digunakan" @selected(request('kondisi') == 'rusak dapat digunakan')>Rusak Dapat Digunakan</option>
                        <option value="rusak tidak dapat digunakan" @selected(request('kondisi') == 'rusak tidak dapat digunakan')>Rusak Tidak Dapat Digunakan</option>
                    </select>
                </div>
                <div>
                    <select name="status_operasional" onchange="this.form.submit()" class="rounded-lg border border-gray-300 py-2.5 px-3 text-sm">
                        <option value="">Semua status ops</option>
                        <option value="aktif" @selected(request('status_operasional') == 'aktif')>Aktif</option>
                        <option value="non aktif" @selected(request('status_operasional') == 'non aktif')>Non Aktif</option>
                    </select>
                </div>
                <div>
                    <select name="lokasi" onchange="this.form.submit()" class="rounded-lg border border-gray-300 py-2.5 px-3 text-sm">
                        <option value="">Semua lokasi</option>
                        @foreach(($lokasi ?? []) as $lok)
                            <option value="{{ $lok }}" @selected(request('lokasi') == $lok)>{{ $lok }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <a href="{{ route('manage-server-storage') }}" class="inline-block border-2 border-[#004A54] text-[#004A54] px-5 py-2 rounded-lg text-sm font-bold hover:bg-cyan-50">Reset</a>
                </div>
            </form>
        </div>

        {{-- TABLE --}}
        <div class="mx-6 mt-4 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-10">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold uppercase tracking-wider text-gray-500">
                            <th class="px-5 py-4">Aset & Perangkat</th>
                            <th class="px-5 py-4">Hostname & IP</th>
                            <th class="px-5 py-4">Spesifikasi (CPU/RAM)</th>
                            <th class="px-5 py-4">Storage Array</th>
                            <th class="px-5 py-4">Lokasi Aset</th>
                            <th class="px-5 py-4">Kondisi & Ops</th>
                            <th class="px-5 py-4 text-right sticky right-0 bg-gray-50 shadow-l">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse(($servers ?? []) as $server)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-[#004A54]">{{ $server->id_aset ?? '-' }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ $server->merk ?? '-' }} {{ $server->model ?? '' }}</div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-gray-700">{{ $server->hostname ?? '-' }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ $server->ip_address ?? '-' }}</div>
                                </td>
                                <td class="px-5 py-4 text-xs text-gray-700">
                                    <div>CPU: <span class="font-medium">{{ $server->cpu_controller ?? '-' }}</span></div>
                                    <div class="text-[10px] text-gray-500 mt-0.5">RAM: {{ $server->ram ?? '-' }} | Firmware: {{ $server->versi_firmware ?? '-' }}</div>
                                </td>
                                <td class="px-5 py-4 text-xs text-gray-700">
                                    <div class="font-medium text-gray-800">{{ $server->kapasitas_total_storage ?? '-' }}</div>
                                    <div class="text-[10px] text-gray-500 mt-0.5" title="{{ $server->disk_array }}">Array: <span class="truncate max-w-[120px] inline-block align-bottom">{{ $server->disk_array ?? '-' }}</span></div>
                                </td>
                                <td class="px-5 py-4 text-xs text-gray-700">
                                    <div class="font-medium">{{ $server->lokasi_aset_saat_ini ?? '-' }}</div>
                                    <div class="text-[10px] text-gray-400 mt-0.5">{{ $server->keterangan_lokasi_aset ?? '-' }}</div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-1.5">
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-medium {{ ($server->status_kondisi ?? 'baik') == 'baik' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                            {{ ucfirst($server->status_kondisi ?? 'baik') }}
                                        </span>
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-medium {{ ($server->status_operasional ?? 'aktif') == 'aktif' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                                            {{ ucfirst($server->status_operasional ?? 'aktif') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-right sticky right-0 bg-white shadow-l">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('manage-server-storage.edit', $server->id) }}" title="Edit" class="w-9 h-9 flex items-center justify-center rounded-md border border-gray-200 text-gray-500 hover:text-[#004A54] hover:border-[#004A54]">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </a>
                                        <form action="{{ route('manage-server-storage.destroy', $server->id) }}" method="POST" onsubmit="return confirm('Hapus server storage {{ $server->id_aset }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" title="Hapus" class="w-9 h-9 flex items-center justify-center rounded-md border border-gray-200 text-gray-500 hover:text-red-600 hover:border-red-300">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7"/><path stroke-linecap="round" stroke-linejoin="round" d="M5 7h14M10 11v6M14 11v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-16 text-center text-gray-400">Belum ada data Server Storage.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(isset($servers) && method_exists($servers, 'total'))
                <div class="px-5 py-4 border-t border-gray-100 text-sm text-gray-500 flex items-center justify-between">
                    <div>Menampilkan {{ $servers->firstItem() ?? 0 }} - {{ $servers->lastItem() ?? 0 }} dari {{ $servers->total() }} data</div>
                    <div>{{ $servers->onEachSide(1)->links() }}</div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection