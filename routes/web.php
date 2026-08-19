<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AddUserController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\FirewallController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AccessPointController;
use App\Http\Controllers\RouterController;
use App\Http\Controllers\SwitchController;
use App\Http\Controllers\ServerBaremetalController;
use App\Http\Controllers\ServerFisikController;
use App\Http\Controllers\ModemController;
use App\Http\Controllers\ServerStorageController;
use App\Http\Controllers\UpsController;
use App\Http\Controllers\WirelessLanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (GUEST)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
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
    // MANAGE TOWER (SUTT)
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

    // ==========================================
    // MANAGE ROUTER
    // ==========================================
    Route::prefix('manage-asset/router')->group(function () {
        Route::get('/', [RouterController::class, 'index'])->name('manage-router');
        Route::get('/create', [RouterController::class, 'create'])->name('manage-router.create');
        Route::post('/', [RouterController::class, 'store'])->name('manage-router.store');
        Route::get('/import', [RouterController::class, 'importForm'])->name('manage-router.import.form');
        Route::post('/import', [RouterController::class, 'import'])->name('manage-asset.router.import');        
        Route::get('/{id}/edit', [RouterController::class, 'edit'])->name('manage-router.edit');
        Route::patch('/{id}', [RouterController::class, 'update'])->name('manage-router.update');
        Route::delete('/{id}', [RouterController::class, 'destroy'])->name('manage-router.destroy');
    });

    // ==========================================
    // MANAGE SWITCH
    // ==========================================
    Route::prefix('manage-asset/switch')->group(function () {
        Route::get('/', [SwitchController::class, 'index'])->name('manage-switch');
        Route::get('/create', [SwitchController::class, 'create'])->name('manage-asset.switch.create');
        Route::post('/', [SwitchController::class, 'store'])->name('manage-switch.store');
        Route::get('/import', [SwitchController::class, 'importForm'])->name('manage-asset.switch.import');
        Route::post('/import', [SwitchController::class, 'importStore'])->name('manage-asset.switch.import.store');
        Route::get('/{id}/edit', [SwitchController::class, 'edit'])->name('manage-switch.edit');
        Route::patch('/{id}', [SwitchController::class, 'update'])->name('manage-switch.update');
        Route::delete('/{id}', [SwitchController::class, 'destroy'])->name('manage-switch.destroy');
    });

    // ==========================================
    // MANAGE ACCESS POINT
    // ==========================================
    Route::prefix('manage-asset/access-point')->group(function () {
        Route::get('/', [AccessPointController::class, 'index'])->name('manage-access-point');
        Route::get('/create', [AccessPointController::class, 'create'])->name('manage-access-point.create');
        Route::post('/', [AccessPointController::class, 'store'])->name('manage-access-point.store');
        Route::get('/import', [AccessPointController::class, 'importForm'])->name('manage-access-point.import.form');
        Route::post('/import', [AccessPointController::class, 'importStore'])->name('manage-asset.access-point.import'); 
        Route::get('/{accessPoint}/edit', [AccessPointController::class, 'edit'])->name('manage-access-point.edit');
        Route::patch('/{accessPoint}', [AccessPointController::class, 'update'])->name('manage-access-point.update');
        Route::delete('/{accessPoint}', [AccessPointController::class, 'destroy'])->name('manage-access-point.destroy');
    });
    
    // ==========================================
    // MANAGE FIREWALL
    // ==========================================
    Route::prefix('manage-asset/firewall')->group(function () {
        Route::get('/', [FirewallController::class, 'index'])->name('manage-firewall');
        Route::get('/create', [FirewallController::class, 'create'])->name('manage-asset.firewall.create');
        Route::post('/', [FirewallController::class, 'store'])->name('manage-asset.firewall.store');
        Route::get('/import', [FirewallController::class, 'importForm'])->name('manage-asset.firewall.import');
        Route::post('/import', [FirewallController::class, 'importStore'])->name('manage-asset.firewall.import.process'); 
        Route::get('/{id}/edit', [FirewallController::class, 'edit'])->name('manage-asset.firewall.edit');
        Route::patch('/{id}', [FirewallController::class, 'update'])->name('manage-asset.firewall.update');
        Route::delete('/{id}', [FirewallController::class, 'destroy'])->name('manage-asset.firewall.destroy');
    });

    // ==========================================
    // MANAGE MODEM
    // ==========================================
    Route::prefix('manage-asset/modem')->name('manage-modem')->group(function () {
        Route::get('/', [ModemController::class, 'index']);
        Route::get('/create', [ModemController::class, 'create'])->name('.create');
        Route::post('/store', [ModemController::class, 'store'])->name('.store');
        Route::get('/import', [ModemController::class, 'importForm'])->name('.import.form');
        Route::post('/import', [ModemController::class, 'importStore'])->name('.import.store');
        Route::get('/{modem}/edit', [ModemController::class, 'edit'])->name('.edit');
        Route::patch('/{modem}', [ModemController::class, 'update'])->name('.update');
        Route::delete('/{modem}', [ModemController::class, 'destroy'])->name('.destroy');
    });

    // ==========================================
    // MANAGE SERVER BAREMETAL
    // ==========================================
    Route::prefix('manage-asset/server-baremetal')->name('manage-server-baremetal')->group(function () {
        Route::get('/', [ServerBaremetalController::class, 'index']);
        Route::get('/create', [ServerBaremetalController::class, 'create'])->name('.create');
        Route::post('/store', [ServerBaremetalController::class, 'store'])->name('.store');
        Route::get('/import', [ServerBaremetalController::class, 'importForm'])->name('.import.form');
        Route::post('/import', [ServerBaremetalController::class, 'importStore'])->name('.import.store');
        Route::get('/{server}/edit', [ServerBaremetalController::class, 'edit'])->name('.edit');
        Route::patch('/{server}', [ServerBaremetalController::class, 'update'])->name('.update');
        Route::delete('/{server}', [ServerBaremetalController::class, 'destroy'])->name('.destroy');
    });

    // ==========================================
    // MANAGE SERVER FISIK
    // ==========================================
    Route::prefix('manage-asset/server-fisik')->name('manage-server-fisik')->group(function () {
        Route::get('/', [ServerFisikController::class, 'index']);
        Route::get('/create', [ServerFisikController::class, 'create'])->name('.create');
        Route::post('/store', [ServerFisikController::class, 'store'])->name('.store');
        Route::get('/import', [ServerFisikController::class, 'importForm'])->name('.import.form');
        Route::post('/import', [ServerFisikController::class, 'importStore'])->name('.import.store');
        Route::get('/{serverFisik}/edit', [ServerFisikController::class, 'edit'])->name('.edit');
        Route::patch('/{serverFisik}', [ServerFisikController::class, 'update'])->name('.update');
        Route::delete('/{serverFisik}', [ServerFisikController::class, 'destroy'])->name('.destroy');
    });

    // ==========================================
    // MANAGE SERVER STORAGE
    // ==========================================
    Route::prefix('manage-asset/server-storage')->name('manage-server-storage')->group(function () {
        Route::get('/', [ServerStorageController::class, 'index']);
        Route::get('/create', [ServerStorageController::class, 'create'])->name('.create');
        Route::post('/store', [ServerStorageController::class, 'store'])->name('.store');
        Route::get('/import', [ServerStorageController::class, 'importForm'])->name('.import.form');
        Route::post('/import', [ServerStorageController::class, 'importStore'])->name('.import.store');
        Route::get('/{serverStorage}/edit', [ServerStorageController::class, 'edit'])->name('.edit');
        Route::patch('/{serverStorage}', [ServerStorageController::class, 'update'])->name('.update');
        Route::delete('/{serverStorage}', [ServerStorageController::class, 'destroy'])->name('.destroy');
    });

    // ==========================================
    // MANAGE SERVER UPS
    // ==========================================
    Route::prefix('manage-asset/ups')->name('manage-ups')->group(function () {
        Route::get('/', [UpsController::class, 'index']);
        Route::get('/create', [UpsController::class, 'create'])->name('.create');
        Route::post('/store', [UpsController::class, 'store'])->name('.store');
        Route::get('/import', [UpsController::class, 'importForm'])->name('.import.form');
        Route::post('/import', [UpsController::class, 'importStore'])->name('.import.store');
        Route::get('/{ups}/edit', [UpsController::class, 'edit'])->name('.edit');
        Route::patch('/{ups}', [UpsController::class, 'update'])->name('.update');
        Route::delete('/{ups}', [UpsController::class, 'destroy'])->name('.destroy');
    });

    // ==========================================
    // MANAGE WIRELESS LAN
    // ==========================================
    Route::prefix('manage-asset/wireless-lan')->name('manage-wireless-lan')->group(function () {
        Route::get('/', [WirelessLanController::class, 'index']);
        Route::get('/create', [WirelessLanController::class, 'create'])->name('.create');
        Route::post('/store', [WirelessLanController::class, 'store'])->name('.store');
        Route::get('/import', [WirelessLanController::class, 'importForm'])->name('.import.form');
        Route::post('/import', [WirelessLanController::class, 'importStore'])->name('.import.store');
        Route::get('/{wirelessLan}/edit', [WirelessLanController::class, 'edit'])->name('.edit');
        Route::patch('/{wirelessLan}', [WirelessLanController::class, 'update'])->name('.update');
        Route::delete('/{wirelessLan}', [WirelessLanController::class, 'destroy'])->name('.destroy');
    });

    // ==============================================================================
    // FALLBACK KATEGORI DINAMIS (HARUS SELALU DI PALING BAWAH GROUP MANAGE-ASSET)
    // ==============================================================================
    Route::get('/manage-asset/{category:slug}', [AssetController::class, 'indexByCategory'])->name('manage-asset.category');

});