@extends('layouts.app', ['title' => 'Import Daftar Pengguna — PLN Asset Management'])

@section('content')
<div class="min-h-screen flex bg-gray-50">
    <div class="flex-1 min-w-0">
        <main class="p-6 lg:p-10 space-y-5 w-full">
            
            {{-- Breadcrumbs Navigation --}}
            <nav class="flex items-center gap-1.5 text-sm">
                <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-[#004A54]">Dashboard</a>
                <svg class="w-3.5 h-3.5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                <a href="{{ route('manage-user') }}" class="text-gray-400 hover:text-[#004A54]">Manage User</a>
                <svg class="w-3.5 h-3.5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                <span class="font-semibold text-gray-700">Import User</span>
            </nav>

            <h1 class="text-2xl font-bold text-pln-800">Import Data User dari CSV</h1>

            {{-- Validation Error Messages --}}
            @if ($errors->any())
                <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Skipped Rows Error Messages --}}
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

            {{-- Main Form Card --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="flex items-start gap-4 p-8 pb-6">
                    <div class="w-11 h-11 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-pln-800">Upload File CSV User</h2>
                        <p class="text-sm text-gray-400">Unggah satu atau beberapa file data User secara massal sekaligus.</p>
                    </div>
                </div>

                <div class="border-t border-gray-100 p-8 space-y-5">
                    
                    <form action="{{ route('manage-user.import.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-6">
                            <label class="block text-sm font-semibold mb-1 text-gray-700">File CSV (Bisa pilih lebih dari satu)</label>
                            
                            {{-- Input file dengan multiple dan nama array files[] --}}
                            <input type="file" name="files[]" id="fileInput" accept=".xlsx,.csv,.txt" class="w-full border border-gray-300 rounded-lg p-2 text-sm bg-white file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-[#004A54] file:text-white hover:file:bg-[#003840] cursor-pointer" multiple required>                            
                            <p class="text-xs text-gray-400 mt-2">Tekan dan tahan tombol Ctrl (Windows) atau Cmd (Mac) di keyboard untuk memilih banyak file CSV sekaligus sesuai template kolom User (No, Nama User, Keterangan).</p>
                            
                            {{-- Daftar preview file yang dipilih --}}
                            <div id="fileListContainer" class="hidden mt-3 p-3 bg-gray-50 border border-gray-200 rounded-lg text-xs text-gray-600">
                                <p class="font-semibold mb-1 text-[#004A54]">File yang dipilih (<span id="fileCount">0</span> file):</p>
                                <ul id="fileNamesList" class="list-disc list-inside space-y-0.5"></ul>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-4">
                            <a href="{{ route('manage-user') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">Batal</a>
                            <button type="submit" class="bg-[#004A54] hover:bg-[#003840] text-white px-5 py-2 rounded-lg text-sm font-bold shadow-sm transition-colors">Proses Import Semua</button>
                        </div>
                    </form>

                </div>
            </div>

        </main>
    </div>
</div>

<script>
    const fileInput = document.getElementById('fileInput');
    const fileListContainer = document.getElementById('fileListContainer');
    const fileNamesList = document.getElementById('fileNamesList');
    const fileCount = document.getElementById('fileCount');

    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            fileNamesList.innerHTML = '';
            const files = e.target.files;
            
            if (files.length > 0) {
                fileListContainer.classList.remove('hidden');
                fileCount.textContent = files.length;
                
                for (let i = 0; i < files.length; i++) {
                    const fileName = files[i].name;
                    const li = document.createElement('li');
                    li.textContent = fileName;
                    fileNamesList.appendChild(li);
                }
            } else {
                fileListContainer.classList.add('hidden');
            }
        });
    }
</script>
@endsection