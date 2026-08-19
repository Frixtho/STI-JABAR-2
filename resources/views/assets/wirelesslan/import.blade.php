@extends('layouts.app', ['title' => 'Import WLC — PLN Financial'])

@section('content')
<div class="min-h-screen flex bg-gray-50">
    <div class="flex-1 min-w-0">
        <main class="p-6 lg:p-10 space-y-5 w-full">
            <nav class="flex items-center gap-1.5 text-sm">
                <a href="{{ route('manage-wireless-lan') }}" class="text-gray-400 hover:text-[#004A54]">WLC</a>
                <svg class="w-3.5 h-3.5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                <span class="font-semibold text-gray-700">Import Data Wireless LAN Controller</span>
            </nav>

            <h1 class="text-2xl font-bold text-pln-800">Import Data WLC dari Excel/CSV</h1>

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

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="flex items-start gap-4 p-8 pb-6">
                    <div class="w-11 h-11 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" /></svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-pln-800">Upload File Excel / CSV WLC</h2>
                        <p class="text-sm text-gray-400">Unggah file data secara massal berdasarkan template standar Wireless LAN Controller.</p>
                    </div>
                </div>

                <div class="border-t border-gray-100 p-8 space-y-5">
                    <form action="{{ route('manage-wireless-lan.import.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-6">
                            <label class="block text-sm font-semibold mb-1 text-gray-700">Pilih File CSV</label>
                            <input type="file" name="files[]" accept=".csv,.txt" class="w-full border border-gray-300 rounded-lg p-2 text-sm bg-white file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:bg-[#004A54] file:text-white cursor-pointer" multiple required>
                        </div>
                        <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-4">
                            <a href="{{ route('manage-wireless-lan') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-50">Batal</a>
                            <button type="submit" class="bg-[#004A54] hover:bg-[#003840] text-white px-5 py-2 rounded-lg text-sm font-medium">Proses Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection