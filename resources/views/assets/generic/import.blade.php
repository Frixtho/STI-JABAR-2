@extends('layouts.app', ['title' => 'Import ' . $currentCategory->name])

@section('content')
<main class="p-6 max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Import CSV: {{ $currentCategory->name }}</h1>
        <p class="text-sm text-gray-500">Pastikan format CSV Anda memiliki Kolom 1 untuk Nama Aset dan Kolom 2 untuk Kode.</p>
    </div>

    <form action="{{ route('manage-asset.generic.import.store', $currentCategory->slug) }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Upload File CSV</label>
            <input type="file" name="files[]" multiple required accept=".csv" class="w-full border border-gray-300 p-2 rounded-md">
        </div>
        
        <div class="pt-4 flex justify-end gap-3">
            <a href="{{ route('manage-asset.generic.index', $currentCategory->slug) }}" class="px-4 py-2 border rounded-md hover:bg-gray-50">Batal</a>
            <button type="submit" class="bg-[#004A54] text-white px-4 py-2 rounded-md">Mulai Import</button>
        </div>
    </form>
</main>
@endsection