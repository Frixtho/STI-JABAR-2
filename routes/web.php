<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AddUserController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\SettingsController;
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
    Route::delete('manage-user/{id}', [ManageUserController::class, 'destroy'])->name('manage-user.destroy');

    // Add User
    Route::get('manage-user/create', [AddUserController::class, 'create'])->name('manage-user.create');
    Route::post('manage-user/store', [AddUserController::class, 'store'])->name('manage-user.store');
});