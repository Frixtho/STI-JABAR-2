<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AddUserController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ManageUserController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

Route::get('manage-user', function () {
    // Mengambil semua data user, atau gunakan paginate(7) agar pas dengan pagination di UI kamu
    $users = User::orderBy('created_at', 'desc')->paginate(7);
    
    return view('manageUser', compact('users'));
})->name('manage-user');

// Form Tambah User
Route::get('manage-user/create', [AddUserController::class, 'create'])->name('manage-user.create');

// Proses Simpan User Baru
Route::post('manage-user/store', [AddUserController::class, 'store'])->name('manage-user.store');

Route::get('manage-user', function (Request $request) {
    // Ambil kata kunci dari input 'search'
    $search = $request->query('search');

    // Query dasar untuk mengambil data user
    $query = User::orderBy('created_at', 'desc');

    // Jika user mengetikkan sesuatu di kolom search
    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('email', 'LIKE', '%' . $search . '%')
              ->orWhere('name', 'LIKE', '%' . $search . '%');
        });
    }

    // Paginate hasil pencarian (7 data per halaman)
    $users = $query->paginate(7)->withQueryString(); // withQueryString agar pagination tidak mereset pencarian

    return view('manageUser', compact('users'));
})->name('manage-user');

// Route untuk menampilkan halaman utama + filtering
Route::get('/manage-user', [ManageUserController::class, 'index'])->name('manage-user');

// Route untuk mengeksekusi penghapusan data
Route::delete('/manage-user/{id}', [ManageUserController::class, 'destroy'])->name('manage-user.destroy');

});