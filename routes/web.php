<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AddUserController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AccessPointController;
use App\Http\Controllers\RouterController;
use App\Http\Controllers\SwitchController;
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
    // MANAGE USER
    // ==========================================
    Route::get('manage-user/create', [AddUserController::class, 'create'])->name('manage-user.create');
    Route::post('manage-user/store', [AddUserController::class, 'store'])->name('manage-user.store');
    Route::get('manage-user', [ManageUserController::class, 'index'])->name('manage-user');
    Route::get('manage-user/{id}/edit', [ManageUserController::class, 'edit'])->name('manage-user.edit');
    Route::patch('manage-user/{id}', [ManageUserController::class, 'update'])->name('manage-user.update');
    Route::delete('manage-user/{id}', [ManageUserController::class, 'destroy'])->name('manage-user.destroy');

    // ==========================================
    // MANAGE UNIT
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
    // MANAGE ROUTER
    // ==========================================
    Route::get('/manage-asset/router', [RouterController::class, 'index'])->name('manage-router');
    Route::get('/manage-asset/router/create', [RouterController::class, 'create'])->name('manage-router.create');
    Route::post('/manage-asset/router', [RouterController::class, 'store'])->name('manage-router.store');
    Route::get('/manage-asset/router/{id}/edit', [RouterController::class, 'edit'])->name('manage-router.edit');
    Route::patch('/manage-asset/router/{id}', [RouterController::class, 'update'])->name('manage-router.update');
    Route::delete('/manage-asset/router/{id}', [RouterController::class, 'destroy'])->name('manage-router.destroy');

    // ==========================================
    // MANAGE SWITCH
    // ==========================================
    Route::get('/manage-asset/switch', [SwitchController::class, 'index'])->name('manage-switch');
    Route::get('/manage-asset/switch/create', [SwitchController::class, 'create'])->name('manage-switch.create');
    Route::post('/manage-asset/switch', [SwitchController::class, 'store'])->name('manage-switch.store');
    Route::get('/manage-asset/switch/{id}/edit', [SwitchController::class, 'edit'])->name('manage-switch.edit');
    Route::patch('/manage-asset/switch/{id}', [SwitchController::class, 'update'])->name('manage-switch.update');
    Route::delete('/manage-asset/switch/{id}', [SwitchController::class, 'destroy'])->name('manage-switch.destroy');

    // ==========================================
    // MANAGE TOWER
    // ==========================================
    Route::get('manage-tower', [AssetController::class, 'indexTower'])->name('manage-tower');
    Route::get('manage-asset/tower/{tower}/edit', [AssetController::class, 'editTower'])->name('manage-asset.tower.edit');
    Route::patch('manage-asset/tower/{tower}', [AssetController::class, 'updateTower'])->name('manage-asset.tower.update');
    Route::delete('manage-asset/tower/{tower}', [AssetController::class, 'destroyTower'])->name('manage-asset.tower.destroy');

    // ==========================================
    // MANAGE ASSET (SUTT & Umum)
    // ==========================================
    Route::get('manage-asset/history', [AssetController::class, 'history'])->name('manage-asset.history');
    Route::get('manage-asset/create', [AssetController::class, 'create'])->name('manage-asset.create');
    Route::get('manage-asset/import', [AssetController::class, 'importForm'])->name('manage-asset.import.form');
    Route::post('manage-asset/import', [AssetController::class, 'import'])->name('manage-asset.import');
    Route::post('manage-asset', [AssetController::class, 'store'])->name('manage-asset.store');
    Route::get('manage-asset', [AssetController::class, 'index'])->name('manage-asset');

    // ==========================================
    // MANAGE ACCESS POINT
    // ==========================================

    Route::get(
        'manage-asset/access-point',
        [AccessPointController::class, 'index']
    )->name('manage-access-point');

    Route::get(
        'manage-asset/access-point/create',
        [AccessPointController::class, 'create']
    )->name('manage-access-point.create');

    Route::post(
        'manage-asset/access-point',
        [AccessPointController::class, 'store']
    )->name('manage-access-point.store');

    Route::get(
        'manage-asset/access-point/{accessPoint}/edit',
        [AccessPointController::class, 'edit']
    )->name('manage-access-point.edit');

    Route::patch(
        'manage-asset/access-point/{accessPoint}',
        [AccessPointController::class, 'update']
    )->name('manage-access-point.update');

    Route::delete(
        'manage-asset/access-point/{accessPoint}',
        [AccessPointController::class, 'destroy']
    )->name('manage-access-point.destroy');

    // ==========================================
    // MANAGE ASSET BY ID
    // ==========================================
    Route::get('manage-asset/{id}/edit', [AssetController::class, 'edit'])->where('id', '[0-9]+') ->name('manage-asset.edit');
    Route::patch('manage-asset/{id}', [AssetController::class, 'update'])->where('id', '[0-9]+')->name('manage-asset.update');
    Route::delete('manage-asset/{id}', [AssetController::class, 'destroy'])->where('id', '[0-9]+')->name('manage-asset.destroy');
    Route::get('manage-asset/{id}', [AssetController::class, 'show'])->where('id', '[0-9]+')->name('manage-asset.show');


    // ==========================================
    // MANAGE ASSET CATEGORY
    // ==========================================
    Route::get('manage-asset/{category:slug}',[AssetController::class, 'indexByCategory'])->name('manage-asset.category');
});