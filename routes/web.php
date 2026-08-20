<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AddUserController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssetCategoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (GUEST)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

Route::prefix('manage-category')->group(function () {
    Route::get('/', [AssetCategoryController::class, 'index'])->name('manage-category');
    Route::post('/', [AssetCategoryController::class, 'store'])->name('manage-category.store');
    Route::patch('/{id}', [AssetCategoryController::class, 'update'])->name('manage-category.update');
    Route::delete('/{id}', [AssetCategoryController::class, 'destroy'])->name('manage-category.destroy');
    Route::patch('/{id}/toggle', [AssetCategoryController::class, 'toggleStatus'])->name('manage-category.toggle');
    Route::get('/{id}/fields', [AssetCategoryController::class, 'fieldsIndex'])->name('manage-category.fields');
    Route::post('/{id}/fields', [AssetCategoryController::class, 'fieldsStore'])->name('manage-category.fields.store');
    Route::delete('/fields/{fieldId}', [AssetCategoryController::class, 'fieldsDestroy'])->name('manage-category.fields.destroy');
    Route::get('/{id}/unit-settings', [AssetCategoryController::class, 'unitSettings'])->name('manage-category.unit-settings');
    Route::patch('/{id}/unit-settings', [AssetCategoryController::class, 'saveUnitSettings'])->name('manage-category.unit-settings.save');
    Route::patch('/fields/{fieldId}/update', [AssetCategoryController::class, 'fieldsUpdate'])->name('manage-category.fields.update');
    Route::patch('/fields/{fieldId}/toggle-table', [AssetCategoryController::class, 'toggleShowInTable'])->name('manage-category.fields.toggle-table');
});

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // ==========================================
    // DASHBOARD
    // ==========================================
    Route::get('dashboard', function () {
        $totalTowers = \Illuminate\Support\Facades\Schema::hasTable('assets') ? \Illuminate\Support\Facades\DB::table('assets')->where('category', 'sutt')->sum('jumlah_tower') : 0;        
        $totalAPs = \Illuminate\Support\Facades\Schema::hasTable('access_points') ? \Illuminate\Support\Facades\DB::table('access_points')->count() : 0;
        $totalRouters = \Illuminate\Support\Facades\Schema::hasTable('routers') ? \Illuminate\Support\Facades\DB::table('routers')->count() : 0;
        
        $baremetal = \Illuminate\Support\Facades\Schema::hasTable('server_baremetals') ? \Illuminate\Support\Facades\DB::table('server_baremetals')->count() : 0;
        $fisik = \Illuminate\Support\Facades\Schema::hasTable('server_fisiks') ? \Illuminate\Support\Facades\DB::table('server_fisiks')->count() : 0;
        $totalServers = $baremetal + $fisik;

        $recentLines = \Illuminate\Support\Facades\Schema::hasTable('assets') ? \Illuminate\Support\Facades\DB::table('assets')
            ->where('category', 'sutt')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get() : collect([]);

        $totalIT = $totalAPs + $totalRouters + $totalServers;
        $totalIT = $totalIT > 0 ? $totalIT : 1; 
        $distribution = (object)[
            'ap' => $totalAPs,
            'ap_pct' => round(($totalAPs / $totalIT) * 100),
            'router' => $totalRouters,
            'router_pct' => round(($totalRouters / $totalIT) * 100),
            'server' => $totalServers,
            'server_pct' => round(($totalServers / $totalIT) * 100),
        ];

        $recentHistories = \Illuminate\Support\Facades\Schema::hasTable('asset_histories') ? \Illuminate\Support\Facades\DB::table('asset_histories')
            ->leftJoin('users', 'asset_histories.user_id', '=', 'users.id')
            ->select('asset_histories.*', 'users.name as user_name')
            ->orderBy('asset_histories.created_at', 'desc')
            ->limit(5)
            ->get() : collect([]);

        return view('dashboard', compact('totalTowers', 'totalAPs', 'totalRouters', 'totalServers', 'recentLines', 'distribution', 'recentHistories'));
    })->name('dashboard');

    // ==========================================
    // SETTINGS
    // ==========================================
    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingsController::class, 'edit'])->name('settings');
        Route::patch('/', [SettingsController::class, 'update'])->name('settings.update');
        Route::patch('/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
        Route::delete('/sessions', [SettingsController::class, 'logoutOtherDevices'])->name('settings.sessions.destroy');
    });

    // ==========================================
    // MANAGE USER
    // ==========================================
    Route::prefix('manage-user')->group(function () {
        Route::get('/', [AddUserController::class, 'index'])->name('manage-user');
        Route::get('/create', [AddUserController::class, 'create'])->name('manage-user.create');
        Route::post('/store', [AddUserController::class, 'store'])->name('manage-user.store');
        Route::get('/import', [AddUserController::class, 'importForm'])->name('manage-user.import.form');
        Route::post('/import', [AddUserController::class, 'importStore'])->name('manage-user.import.store');
        Route::get('/{user}/edit', [AddUserController::class, 'edit'])->name('manage-user.edit');
        Route::patch('/{user}', [AddUserController::class, 'update'])->name('manage-user.update');
        Route::delete('/{user}', [AddUserController::class, 'destroy'])->name('manage-user.destroy');
    });

    // ==========================================
    // MANAGE UNIT
    // ==========================================
    Route::prefix('manage-unit')->group(function () {
        Route::get('/', [UnitController::class, 'index'])->name('manage-unit');
        Route::get('/create', [UnitController::class, 'create'])->name('manage-unit.create');
        Route::post('/', [UnitController::class, 'store'])->name('manage-unit.store');
        Route::get('/import', [UnitController::class, 'importForm'])->name('manage-unit.import.form');
        Route::post('/import', [UnitController::class, 'import'])->name('manage-unit.import');
        Route::get('/{unit}/edit', [UnitController::class, 'edit'])->name('manage-unit.edit');
        Route::patch('/{unit}', [UnitController::class, 'update'])->name('manage-unit.update');
        Route::delete('/{unit}', [UnitController::class, 'destroy'])->name('manage-unit.destroy');
        Route::get('/{unit}/distance-bandung', [UnitController::class, 'distanceToBandung'])->name('manage-unit.distance');
    });


    /*
    |--------------------------------------------------------------------------
    | MANAGE ASSETS
    |--------------------------------------------------------------------------
    */
    
    // ==========================================
    // ASSET CORE & HISTORY
    // ==========================================
    Route::get('/manage-asset', [AssetController::class, 'indexTower'])->name('manage-asset'); 
    Route::get('/manage-asset/history', [AssetController::class, 'history'])->name('manage-asset.history');
    Route::get('/manage-asset/history/export', [AssetController::class, 'exportCsv'])->name('manage-asset.history.export');
    Route::get('/manage-asset/create', [AssetController::class, 'create'])->name('manage-asset.create');
    Route::post('/manage-asset', [AssetController::class, 'store'])->name('manage-asset.store');
    Route::get('/manage-asset/{id}/edit', [AssetController::class, 'edit'])->where('id', '[0-9]+')->name('manage-asset.edit');
    Route::patch('/manage-asset/{id}', [AssetController::class, 'update'])->where('id', '[0-9]+')->name('manage-asset.update');
    
    // ==========================================
    // MANAGE TOWER (SUTT) - HYBRID STATIC ROUTE
    // ==========================================
    Route::prefix('manage-asset/tower')->group(function () {
        Route::get('/', [AssetController::class, 'indexTower'])->name('manage-asset.tower.index');
        Route::get('/import', [AssetController::class, 'importForm'])->name('manage-asset.tower.import.form');
        Route::post('/import', [AssetController::class, 'import'])->name('manage-asset.tower.import.store');
        
        // Show detail Jalur & Hapus Jalur
        Route::get('/{id}', [AssetController::class, 'show'])->where('id', '[0-9]+')->name('manage-asset.show');
        Route::delete('/file/{asset}', [AssetController::class, 'destroy'])->where('asset', '[0-9]+')->name('manage-asset.destroy');

        // CRUD titik tower di dalam jalur
        Route::get('/{tower}/edit', [AssetController::class, 'editTower'])->name('manage-asset.tower.edit');
        Route::patch('/{tower}', [AssetController::class, 'updateTower'])->name('manage-asset.tower.update');
        Route::delete('/{tower}', [AssetController::class, 'destroyTower'])->name('manage-asset.tower.destroy');
    });

    // ==============================================================================
    // FALLBACK KATEGORI DINAMIS (HARUS SELALU DI PALING BAWAH GROUP MANAGE-ASSET)
    // ==============================================================================
    Route::prefix('/manage-asset/{category:slug}')->name('manage-asset.generic.')->group(function () {
        Route::get('/', [AssetController::class, 'indexByCategory'])->name('index');
        Route::get('/create', [AssetController::class, 'createByCategory'])->name('create');
        Route::post('/store', [AssetController::class, 'storeByCategory'])->name('store');
        Route::get('/import', [AssetController::class, 'importFormByCategory'])->name('import.form');
        Route::post('/import', [AssetController::class, 'importStoreByCategory'])->name('import.store');
        Route::get('/{id}/edit', [AssetController::class, 'editByCategory'])->name('edit');
        Route::patch('/{id}', [AssetController::class, 'updateByCategory'])->name('update');
        Route::delete('/{id}', [AssetController::class, 'destroyByCategory'])->name('destroy');
    });
});