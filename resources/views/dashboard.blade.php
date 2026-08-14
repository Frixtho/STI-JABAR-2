@extends('layouts.app', ['title' => 'Dashboard — PLN Asset Management'])

@section('content')
<div class="min-h-screen bg-gray-50 pb-10">
    
    {{-- Header --}}
    <div class="px-8 py-6 flex flex-col md:flex-row justify-between items-start md:items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Selamat {{ (date('H') < 12) ? 'Pagi' : ((date('H') < 15) ? 'Siang' : ((date('H') < 18) ? 'Sore' : 'Malam')) }}, {{ explode(' ', auth()->user()->name)[0] }}</h1>
            <p class="text-sm text-gray-500 mt-1">Ringkasan infrastruktur dan aktivitas sistem hari ini.</p>
        </div>
    </div>

    <div class="px-8 space-y-6 w-full">
        
        {{-- ==================== 1. TOP CARDS ==================== --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            
            {{-- Card 1: Total Towers --}}
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Total Titik Tower</p>
                        <h3 class="text-3xl font-extrabold text-gray-800 mt-1">{{ number_format($totalTowers) }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-teal-50 flex items-center justify-center text-teal-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Card 2: Active Access Points --}}
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Access Point</p>
                        <h3 class="text-3xl font-extrabold text-gray-800 mt-1">{{ number_format($totalAPs) }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-yellow-50 flex items-center justify-center text-yellow-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.288 15.038a5.25 5.25 0 0 1 7.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 0 1 1.06 0Z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Card 3: Router Health Warning --}}
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Router Jaringan</p>
                        <h3 class="text-3xl font-extrabold text-gray-800 mt-1">{{ number_format($totalRouters) }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center text-red-500">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 0 1-3-3m3 3a3 3 0 1 0 0 6h13.5a3 3 0 1 0 0-6m-16.5-3a3 3 0 0 1 3-3h13.5a3 3 0 0 1 3 3m-19.5 0a4.5 4.5 0 0 1 .9-2.7L5.737 5.1a3.375 3.375 0 0 1 2.7-1.35h7.126c1.062 0 2.062.5 2.7 1.35l2.587 3.45a4.5 4.5 0 0 1 .9 2.7" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Card 4: Critical Alerts --}}
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Server Utama</p>
                        <h3 class="text-3xl font-extrabold text-gray-800 mt-1">{{ number_format($totalServers) }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-red-700 flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 17.25v-.228a4.5 4.5 0 0 0-.12-1.03l-2.268-9.64a3.375 3.375 0 0 0-3.285-2.602H7.923a3.375 3.375 0 0 0-3.285 2.602l-2.268 9.64a4.5 4.5 0 0 0-.12 1.03v.228m19.5 0a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3m19.5 0a3 3 0 0 0-3-3H5.25a3 3 0 0 0-3 3m16.5 0h.008v.008h-.008v-.008Zm-3 0h.008v.008h-.008v-.008Z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== 2. MIDDLE SECTION ==================== --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Left: Data Jalur SUTT (Menggantikan Maintenance Schedule) --}}
            <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Jalur SUTT Terbaru</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Data jalur transmisi yang baru diimpor ke sistem</p>
                    </div>
                    <a href="{{ route('manage-asset.tower.index') }}" class="text-sm font-bold text-[#004A54] hover:underline">Lihat Semua</a>
                </div>

                <div class="space-y-3">
                    @forelse($recentLines as $line)
                        <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="w-12 h-12 shrink-0 bg-[#004A54] rounded-lg flex flex-col items-center justify-center text-white">
                                <span class="text-[10px] font-bold uppercase opacity-80">{{ \Carbon\Carbon::parse($line->created_at)->translatedFormat('M') }}</span>
                                <span class="text-lg font-extrabold leading-none">{{ \Carbon\Carbon::parse($line->created_at)->format('d') }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-800">{{ $line->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $line->functloc }} • {{ $line->tegangan ?? '150 kV' }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 italic py-4 text-center">Belum ada jalur SUTT yang ditambahkan.</p>
                    @endforelse
                </div>
            </div>

            {{-- Right: Distribusi Aset IT (Menggantikan Connectivity) --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-6">Distribusi Aset IT</h2>
                
                <div class="mb-8">
                    <div class="flex justify-between items-end mb-2">
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Total Registrasi</span>
                        <span class="text-xs font-bold text-[#004A54]">{{ number_format($distribution->ap + $distribution->router + $distribution->server) }} UNIT</span>
                    </div>
                    <div class="h-3 w-full bg-gray-100 rounded-full flex overflow-hidden">
                        <div class="bg-[#004A54]" style="width: {{ $distribution->ap_pct }}%"></div>
                        <div class="bg-red-500" style="width: {{ $distribution->server_pct }}%"></div>
                        <div class="bg-yellow-400" style="width: {{ $distribution->router_pct }}%"></div>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between items-center bg-gray-50 px-4 py-2.5 rounded-md border border-gray-100">
                        <div class="flex items-center gap-2">
                            <div class="w-2.5 h-2.5 rounded-full bg-[#004A54]"></div>
                            <span class="text-sm font-semibold text-[#004A54]">Access Point</span>
                        </div>
                        <span class="text-sm font-bold text-gray-800">{{ number_format($distribution->ap) }}</span>
                    </div>
                    <div class="flex justify-between items-center bg-red-50 px-4 py-2.5 rounded-md border border-red-100">
                        <div class="flex items-center gap-2">
                            <div class="w-2.5 h-2.5 rounded-full bg-red-500"></div>
                            <span class="text-sm font-semibold text-red-600">Server & Storage</span>
                        </div>
                        <span class="text-sm font-bold text-red-700">{{ number_format($distribution->server) }}</span>
                    </div>
                    <div class="flex justify-between items-center bg-yellow-50 px-4 py-2.5 rounded-md border border-yellow-100">
                        <div class="flex items-center gap-2">
                            <div class="w-2.5 h-2.5 rounded-full bg-yellow-400"></div>
                            <span class="text-sm font-semibold text-yellow-600">Router / Switch</span>
                        </div>
                        <span class="text-sm font-bold text-yellow-700">{{ number_format($distribution->router) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== 3. BOTTOM TABLE ==================== --}}
        {{-- Riwayat Aktivitas (Menggantikan Recent Alerts) --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mt-6">
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h2 class="text-lg font-bold text-gray-800">Riwayat Perubahan Sistem Terakhir</h2>
                <a href="{{ route('manage-asset.history') }}" class="text-sm font-bold text-[#004A54] hover:underline flex items-center gap-1">
                    Lihat Semua &rarr;
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-gray-50/50 text-[10px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4">WAKTU</th>
                            <th class="px-6 py-4">AKSI</th>
                            <th class="px-6 py-4 w-1/2">RINCIAN PERUBAHAN</th>
                            <th class="px-6 py-4">USER</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($recentHistories as $history)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-semibold text-gray-800">
                                    {{ \Carbon\Carbon::parse($history->created_at)->translatedFormat('d M Y') }}
                                    <div class="text-[11px] font-normal text-gray-500">{{ \Carbon\Carbon::parse($history->created_at)->format('H:i:s') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-widest border border-white/50 shadow-sm 
                                        {{ strtoupper($history->action) == 'TAMBAH' ? 'bg-green-100 text-green-700' : (strtoupper($history->action) == 'HAPUS' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                        {{ $history->action }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 whitespace-normal min-w-[300px]">
                                    {{ $history->description }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-800 font-medium">
                                    {{ $history->user_name ?? 'Sistem' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500 italic">Belum ada riwayat aktivitas yang tercatat di database.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection