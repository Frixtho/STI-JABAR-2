<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AddUserController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AccessPointController;
use Illuminate\Support\Facades\Route;

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

    Route::get('settings', [SettingsController::class, 'edit'])->name('settings');
    Route::patch('settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::patch('settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');

    // ==========================================
    // MANAGE USER (Route statis ditaruh di atas)
    // ==========================================
    Route::get('manage-user/create', [AddUserController::class, 'create'])->name('manage-user.create');
    Route::post('manage-user/store', [AddUserController::class, 'store'])->name('manage-user.store');
    
    Route::get('manage-user', [ManageUserController::class, 'index'])->name('manage-user');
    Route::get('manage-user/{id}/edit', [ManageUserController::class, 'edit'])->name('manage-user.edit');
    Route::patch('manage-user/{id}', [ManageUserController::class, 'update'])->name('manage-user.update');
    Route::delete('manage-user/{id}', [ManageUserController::class, 'destroy'])->name('manage-user.destroy');

    // ==========================================
    // MANAGE UNIT (Route statis ditaruh di atas)
    // ==========================================
    Route::get('manage-unit/create', [UnitController::class, 'create'])->name('manage-unit.create');
    Route::get('manage-unit/import', [UnitController::class, 'importForm'])->name('manage-unit.import.form');
    Route::post('manage-unit/import', [UnitController::class, 'import'])->name('manage-unit.import');
    Route::post('manage-unit', [UnitController::class, 'store'])->name('manage-unit.store');
    
    Route::get('manage-unit', [UnitController::class, 'index'])->name('manage-unit');
    Route::get('manage-unit/{unit}/edit', [UnitController::class, 'edit'])->name('manage-unit.edit');
    Route::get('manage-unit/{unit}/distance-bandung', [UnitController::class, 'distanceToBandung'])->name('manage-unit.distance');
    Route::patch('manage-unit/{unit}', [UnitController::class, 'update'])->name('manage-unit.update');
    Route::delete('manage-unit/{unit}', [UnitController::class, 'destroy'])->name('manage-unit.destroy');

    // ==========================================
    // MANAGE ASSET & KATEGORI DINAMIS
    // ==========================================
    // Rute statis ditaruh di atas sebelum parameter dinamis
    Route::get('/manage-asset/history', [AssetController::class, 'history'])->name('manage-asset.history');
    Route::get('manage-asset/create', [AssetController::class, 'create'])->name('manage-asset.create');
    Route::get('manage-asset/import', [AssetController::class, 'importForm'])->name('manage-asset.import.form');
    Route::post('manage-asset/import', [AssetController::class, 'import'])->name('manage-asset.import');
    Route::post('/manage-asset', [AssetController::class, 'store'])->name('manage-asset.store');
    
    Route::get('manage-asset/{gi}/kms', [AssetController::class, 'calculateKms'])->name('manage-asset.kms');
    
    // Rute Tower (Opsional, jika masih dipertahankan terpisah)
    Route::get('manage-tower', [AssetController::class, 'indexTower'])->name('manage-tower');
    Route::get('manage-asset/tower/{tower}/edit', [AssetController::class, 'editTower'])->name('manage-asset.tower.edit');
    Route::patch('manage-asset/tower/{tower}', [AssetController::class, 'updateTower'])->name('manage-asset.tower.update');
    Route::delete('manage-asset/tower/{tower}', [AssetController::class, 'destroyTower'])->name('manage-asset.tower.destroy');

    // Rute Utama / Kategori Dinamis berdasarkan Slug (Contoh: /manage-asset/router, /manage-asset/tower, dll)
    Route::get('manage-asset/{category:slug}', [AssetController::class, 'indexByCategory'])->name('manage-asset.category');

    // Rute hapus aset universal
    Route::delete('manage-asset/{asset}', [AssetController::class, 'destroy'])->name('manage-asset.destroy');

    // ==========================================   
    // MANAGE ACCESS POINT (HAPUS KARENA SUDAH DINAMIS)
    // ==========================================
    Route::get('manage-access-point/create', [AccessPointController::class, 'create'])->name('manage-access-point.create');
    Route::post('manage-access-point', [AccessPointController::class, 'store'])->name('manage-access-point.store');
    Route::get('manage-access-point', [AccessPointController::class, 'index'])->name('manage-access-point');
    Route::get('manage-access-point/{accessPoint}/edit', [AccessPointController::class, 'edit'])->name('manage-access-point.edit');
    Route::patch('manage-access-point/{accessPoint}', [AccessPointController::class, 'update'])->name('manage-access-point.update');
    Route::delete('/manage-access-point/{id}', [AccessPointController::class, 'destroy'])->name('manage-access-point.destroy');
});