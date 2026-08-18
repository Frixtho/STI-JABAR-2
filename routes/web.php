<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AddUserController;
use App\Http\Controllers\ManageUserController;
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
        // 1. TOP CARDS (Data Real dengan pengaman jika tabel belum ada)
        $totalTowers = \Illuminate\Support\Facades\Schema::hasTable('assets') ? \Illuminate\Support\Facades\DB::table('assets')->where('category', 'sutt')->sum('jumlah_tower') : 0;        $totalAPs = \Illuminate\Support\Facades\Schema::hasTable('access_points') ? \Illuminate\Support\Facades\DB::table('access_points')->count() : 0;
        $totalRouters = \Illuminate\Support\Facades\Schema::hasTable('routers') ? \Illuminate\Support\Facades\DB::table('routers')->count() : 0;
        
        $baremetal = \Illuminate\Support\Facades\Schema::hasTable('server_baremetals') ? \Illuminate\Support\Facades\DB::table('server_baremetals')->count() : 0;
        $fisik = \Illuminate\Support\Facades\Schema::hasTable('server_fisiks') ? \Illuminate\Support\Facades\DB::table('server_fisiks')->count() : 0;
        $totalServers = $baremetal + $fisik;

        // 2. MIDDLE LEFT: Jalur SUTT Terbaru (Pengganti Maintenance Schedule)
        $recentLines = \Illuminate\Support\Facades\Schema::hasTable('assets') ? \Illuminate\Support\Facades\DB::table('assets')
            ->where('category', 'sutt')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get() : collect([]);

        // 3. MIDDLE RIGHT: Distribusi Aset (Pengganti Connectivity)
        $totalIT = $totalAPs + $totalRouters + $totalServers;
        $totalIT = $totalIT > 0 ? $totalIT : 1; // Mencegah error pembagian nol
        $distribution = (object)[
            'ap' => $totalAPs,
            'ap_pct' => round(($totalAPs / $totalIT) * 100),
            'router' => $totalRouters,
            'router_pct' => round(($totalRouters / $totalIT) * 100),
            'server' => $totalServers,
            'server_pct' => round(($totalServers / $totalIT) * 100),
        ];

        // 4. BOTTOM TABLE: Riwayat Perubahan (Pengganti Recent Alerts)
        $recentHistories = \Illuminate\Support\Facades\Schema::hasTable('asset_histories') ? \Illuminate\Support\Facades\DB::table('asset_histories')
            ->leftJoin('users', 'asset_histories.user_id', '=', 'users.id')
            ->select('asset_histories.*', 'users.name as user_name')
            ->orderBy('asset_histories.created_at', 'desc')
            ->limit(5)
            ->get() : collect([]);

        return view('dashboard', compact('totalTowers', 'totalAPs', 'totalRouters', 'totalServers', 'recentLines', 'distribution', 'recentHistories'));
    })->name('dashboard');

    Route::get('settings', [SettingsController::class, 'edit'])->name('settings');
    Route::patch('settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::patch('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
    
    // Tambahkan 1 baris ini di bawahnya:
    Route::delete('settings/sessions', [SettingsController::class, 'logoutOtherDevices'])->name('settings.sessions.destroy');

// ==========================================
    // MANAGE USER
    // ==========================================
    // 1. Tampilkan daftar user
    Route::get('manage-user', [ManageUserController::class, 'index'])->name('manage-user');
    
    // 2. Tambah user manual
    Route::get('manage-user/create', [AddUserController::class, 'create'])->name('manage-user.create');
    Route::post('manage-user/store', [AddUserController::class, 'store'])->name('manage-user.store');
    
    // 3. Edit & Hapus user
    Route::get('manage-user/{id}/edit', [ManageUserController::class, 'edit'])->name('manage-user.edit');
    Route::patch('manage-user/{user}', [AddUserController::class, 'update'])->name('manage-user.update');
    Route::patch('manage-user/{id}', [ManageUserController::class, 'update'])->name('manage-user.update');
    Route::delete('manage-user/{id}', [ManageUserController::class, 'destroy'])->name('manage-user.destroy');

    // 4. Import User
    Route::get('manage-user/import', [AddUserController::class, 'importForm'])->name('manage-user.import.form');
    Route::post('manage-user/import', [AddUserController::class, 'importStore'])->name('manage-user.import.store');
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
    Route::get('/manage-asset/router/import', [RouterController::class, 'importForm'])->name('manage-router.import.form');
    Route::post('/manage-asset/router/import', [RouterController::class, 'import'])->name('manage-asset.router.import');        
    Route::post('/manage-asset/router', [RouterController::class, 'store'])->name('manage-router.store');
    Route::get('/manage-asset/router/{id}/edit', [RouterController::class, 'edit'])->name('manage-router.edit');
    Route::patch('/manage-asset/router/{id}', [RouterController::class, 'update'])->name('manage-router.update');
    Route::delete('/manage-asset/router/{id}', [RouterController::class, 'destroy'])->name('manage-router.destroy');

    // ==========================================
    // MANAGE SWITCH
    // ==========================================
    Route::get('/manage-asset/switch', [SwitchController::class, 'index'])->name('manage-switch');
    Route::get('/manage-asset/switch/create', [SwitchController::class, 'create'])->name('manage-asset.switch.create');
    Route::get('/manage-asset/switch/import', [SwitchController::class, 'importForm'])->name('manage-asset.switch.import');
    Route::post('/manage-asset/switch/import', [SwitchController::class, 'importStore'])->name('manage-asset.switch.import.store');
    Route::post('/manage-asset/switch', [SwitchController::class, 'store'])->name('manage-switch.store');
    Route::get('/manage-asset/switch/{id}/edit', [SwitchController::class, 'edit'])->name('manage-switch.edit');
    Route::patch('/manage-asset/switch/{id}', [SwitchController::class, 'update'])->name('manage-switch.update');
    Route::delete('/manage-asset/switch/{id}', [SwitchController::class, 'destroy'])->name('manage-switch.destroy');

    // ==========================================
    // MANAGE ACCESS POINT
    // ==========================================
    Route::get('manage-asset/access-point', [AccessPointController::class, 'index'])->name('manage-access-point');
    Route::get('manage-asset/access-point/create', [AccessPointController::class, 'create'])->name('manage-access-point.create');
    Route::get('manage-asset/access-point/import', [AccessPointController::class, 'importForm'])->name('manage-access-point.import.form');
    Route::post('manage-asset/access-point/import', [AccessPointController::class, 'importStore'])->name('manage-asset.access-point.import'); 
    Route::post('manage-asset/access-point', [AccessPointController::class, 'store'])->name('manage-access-point.store');
    Route::get('manage-asset/access-point/{accessPoint}/edit', [AccessPointController::class, 'edit'])->name('manage-access-point.edit');
    Route::patch('manage-asset/access-point/{accessPoint}', [AccessPointController::class, 'update'])->name('manage-access-point.update');
    Route::delete('manage-asset/access-point/{accessPoint}', [AccessPointController::class, 'destroy'])->name('manage-access-point.destroy');
    
    // ==========================================
    // MANAGE FIREWALL
    // ==========================================
    Route::get('/manage-asset/firewall', [FirewallController::class, 'index'])->name('manage-firewall');
    Route::get('/manage-asset/firewall/create', [FirewallController::class, 'create'])->name('manage-asset.firewall.create');
    Route::get('/manage-asset/firewall/import', [FirewallController::class, 'importForm'])->name('manage-asset.firewall.import');
    Route::post('/manage-asset/firewall/import', [FirewallController::class, 'importStore'])->name('manage-asset.firewall.import.process'); 
    Route::post('/manage-asset/firewall', [FirewallController::class, 'store'])->name('manage-asset.firewall.store');
    Route::get('/manage-asset/firewall/{id}/edit', [FirewallController::class, 'edit'])->name('manage-asset.firewall.edit');
    Route::patch('/manage-asset/firewall/{id}', [FirewallController::class, 'update'])->name('manage-asset.firewall.update');
    Route::delete('/manage-asset/firewall/{id}', [FirewallController::class, 'destroy'])->name('manage-asset.firewall.destroy');

    // ==========================================
    // MANAGE MODEM
    // ==========================================
    Route::prefix('manage-asset/modem')->name('manage-modem')->group(function () {
        Route::get('/', [ModemController::class, 'index']);
        Route::get('/create', [ModemController::class, 'create'])->name('.create');
        Route::post('/store', [ModemController::class, 'store'])->name('.store');
        Route::get('/{modem}/edit', [ModemController::class, 'edit'])->name('.edit');
        Route::patch('/{modem}', [ModemController::class, 'update'])->name('.update');
        Route::delete('/{modem}', [ModemController::class, 'destroy'])->name('.destroy');
        Route::get('/import', [ModemController::class, 'importForm'])->name('.import.form');
        Route::post('/import', [ModemController::class, 'importStore'])->name('.import.store');
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
    // MANAGE SERVER WIRELESS LAN
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

    // ==========================================
    // MANAGE TOWER (URL: /manage-asset/tower)
    // ==========================================
    Route::prefix('manage-asset/tower')->name('manage-asset.tower')->group(function () {
        Route::get('/', [AssetController::class, 'indexTower'])->name('.index');
        Route::get('/import', [AssetController::class, 'importForm'])->name('.import.form');
        Route::post('/import', [AssetController::class, 'import'])->name('.import.store');
        Route::get('/{tower}/edit', [AssetController::class, 'editTower'])->name('.edit');
        Route::patch('/{tower}', [AssetController::class, 'updateTower'])->name('.update');
        Route::delete('/{tower}', [AssetController::class, 'destroyTower'])->name('.destroy');
    });

    // Rute Detail Jalur SUTT (Show) & Hapus Induk Jalur SUTT
    Route::get('manage-asset/tower/{id}', [AssetController::class, 'show'])->where('id', '[0-9]+')->name('manage-asset.show');
    Route::delete('manage-asset/tower/file/{asset}', [AssetController::class, 'destroy'])->where('asset', '[0-9]+')->name('manage-asset.destroy');

    // ==========================================
    // MANAGE ASSET & HISTORY (CORE)
    // ==========================================
    Route::get('/manage-asset/history', [AssetController::class, 'history'])->name('manage-asset.history');
    Route::get('/manage-asset/history/export', [AssetController::class, 'exportCsv'])->name('manage-asset.history.export');

    Route::get('manage-asset/create', [AssetController::class, 'create'])->name('manage-asset.create');
    Route::post('manage-asset', [AssetController::class, 'store'])->name('manage-asset.store');
    
    // Rute Utama /manage-asset dialihkan langsung menampilkan indexTower (Menu Tower)
    Route::get('manage-asset', [AssetController::class, 'indexTower'])->name('manage-asset'); 
    
    Route::get('manage-asset/{id}/edit', [AssetController::class, 'edit'])->where('id', '[0-9]+')->name('manage-asset.edit');
    Route::patch('manage-asset/{id}', [AssetController::class, 'update'])->where('id', '[0-9]+')->name('manage-asset.update');
    Route::get('manage-asset/{category:slug}', [AssetController::class, 'indexByCategory'])->name('manage-asset.category');
});