<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AddUserController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\AssetController;
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

    // Manage User
    Route::get('manage-user', [ManageUserController::class, 'index'])->name('manage-user');
    Route::get('manage-user/{id}/edit', [ManageUserController::class, 'edit'])->name('manage-user.edit');
    Route::patch('manage-user/{id}', [ManageUserController::class, 'update'])->name('manage-user.update');
    Route::delete('manage-user/{id}', [ManageUserController::class, 'destroy'])->name('manage-user.destroy');

    // Add User
    Route::get('manage-user/create', [AddUserController::class, 'create'])->name('manage-user.create');
    Route::post('manage-user/store', [AddUserController::class, 'store'])->name('manage-user.store');

    // Manage Unit
    Route::get('manage-unit', [UnitController::class, 'index'])->name('manage-unit');
    Route::get('manage-unit/create', [UnitController::class, 'create'])->name('manage-unit.create');
    Route::post('manage-unit', [UnitController::class, 'store'])->name('manage-unit.store');
    Route::get('manage-unit/{unit}/edit', [UnitController::class, 'edit'])->name('manage-unit.edit');
    Route::patch('manage-unit/{unit}', [UnitController::class, 'update'])->name('manage-unit.update');
    Route::delete('manage-unit/{unit}', [UnitController::class, 'destroy'])->name('manage-unit.destroy');

    Route::get('manage-unit/import', [UnitController::class, 'importForm'])->name('manage-unit.import.form');
    Route::post('manage-unit/import', [UnitController::class, 'import'])->name('manage-unit.import');

    Route::get('manage-unit/{unit}/distance-bandung', [UnitController::class, 'distanceToBandung'])->name('manage-unit.distance');

    //Manage Asset
    Route::get('manage-asset', [AssetController::class, 'index'])->name('manage-asset');
    Route::delete('manage-asset/{asset}', [AssetController::class, 'destroy'])->name('manage-asset.destroy');
    Route::get('manage-asset/import', [AssetController::class, 'importForm'])->name('manage-asset.import.form');
    Route::post('manage-asset/import', [AssetController::class, 'import'])->name('manage-asset.import');
    Route::get('manage-asset/{gi}/kms', [AssetController::class, 'calculateKms'])->name('manage-asset.kms');
});