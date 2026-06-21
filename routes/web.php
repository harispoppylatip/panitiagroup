<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\controllerpost;
use App\Http\Controllers\controllerscanner;
use App\Http\Controllers\indexcontroller;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\ambiljadwalapi;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\tokencontroller;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\payment\admingrubkas;
use App\Http\Controllers\payment\grubkascontroller;
use App\Http\Controllers\test;
use App\Models\grubkas;

Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware('auth')->prefix('admin')->group(function() {

    Route::middleware('role:admin,akuntan,anggota')->group( function() {
        // management tugas
        Route::get('/tugas', [TugasController::class, 'index'])->name('admin.tugas.index');
        Route::get('/tugas/create', [TugasController::class, 'create'])->name('admin.tugas.create');
        Route::post('/tugas/createnew', [TugasController::class, 'postnew'])->name('admin.tugas.createnew');
        Route::get('/tugas/{id}', [TugasController::class, 'show'])->name('admin.tugas.show');
        Route::get('/tugas/{id}/edit', [TugasController::class, 'edit'])->name('admin.tugas.edit');
        Route::put('/tugas/{id}/edit/now', [TugasController::class, 'update'])->name('admin.tugas.editsend');
        Route::delete('/tugas/delete/{id}', [TugasController::class, 'destroy'])->name('admin.tugas.delete');

        // management beranda
        Route::get('/beranda', [BerandaController::class, 'index'])->name('admin.beranda.index');
        Route::get('/beranda/hero/edit', [BerandaController::class, 'editHero'])->name('admin.beranda.edit-hero');
        Route::put('/beranda/hero/update', [BerandaController::class, 'updateHero'])->name('admin.beranda.update-hero');
        Route::get('/beranda/team/edit', [BerandaController::class, 'editTeam'])->name('admin.beranda.edit-team');
        Route::post('/beranda/team/store', [BerandaController::class, 'storeTeam'])->name('admin.beranda.store-team');
        Route::put('/beranda/team/{teamMember}/update', [BerandaController::class, 'updateTeam'])->name('admin.beranda.update-team');
        Route::delete('/beranda/team/{teamMember}', [BerandaController::class, 'destroyTeam'])->name('admin.beranda.destroy-team');
    });

    Route::middleware('role:admin,akuntan')->group(function () {
        Route::get('/finance', [admingrubkas::class, 'index'])->name('admin.finance.index');
        Route::post('/finance/settings', [admingrubkas::class, 'updateSettings'])->name('admin.finance.settings.update');
        Route::post('/finance/manual-cash', [admingrubkas::class, 'storeManualCash'])->name('admin.finance.manual-cash.store');
        Route::post('/finance/manual-debt', [admingrubkas::class, 'storeManualDebt'])->name('admin.finance.manual-debt.store');
        Route::post('/finance/{nim}/approve', [admingrubkas::class, 'approvePayment'])->name('admin.finance.payment.approve');
        Route::post('/finance/{nim}/reject', [admingrubkas::class, 'rejectPayment'])->name('admin.finance.payment.reject');
        Route::middleware('role:admin')->group(function () {
            Route::post('/finance/reset', [admingrubkas::class, 'resetAll'])->name('admin.finance.reset');
        });

        // token control
        Route::get('/inserttoken', [AdminController::class, 'inserttoken'])->name('admin.inserttoken.form');
        Route::get('/membertoken', [tokencontroller::class, 'index'])->name('admin.membertoken');
        Route::post('/simpan/token', [tokencontroller::class, 'membertokenproses'])->name('admin.savetoken');
        Route::get('/token/{id}/edit', [tokencontroller::class, 'edit'])->name('admin.token.edit');
        Route::put('/token/{id}', [tokencontroller::class, 'update'])->name('admin.token.update');
        Route::delete('/token/del/{id}', [tokencontroller::class, 'destroy'])->name('admin.token.destroy');
        Route::post('/token/refresh-all', [tokencontroller::class, 'refreshAllTokens'])->name('admin.token.refresh-all');
        Route::get('/scan-login-setting', [AdminController::class, 'scanLoginSetting'])->name('admin.scan.login.setting');
        Route::post('/scan-login-setting', [AdminController::class, 'updateScanLoginSetting'])->name('admin.scan.login.setting.update');

        // management user
        Route::resource('/users', AdminUserController::class, [
            'names' => [
                'index' => 'admin.users.index',
                'create' => 'admin.users.create',
                'store' => 'admin.users.store',
                'show' => 'admin.users.show',
                'edit' => 'admin.users.edit',
                'update' => 'admin.users.update',
                'destroy' => 'admin.users.destroy',
            ],
            'parameters' => ['user' => 'user'],
        ]);
    });
});

// pages
Route::get('/', [controllerpost::class, 'home']);
Route::get('/jadwal', [ambiljadwalapi::class, 'index'])->name('jadwal');
Route::get('/tugas', [TugasController::class, 'front'])->name('tugas');

// pembayaran grubkas - static pages only
Route::resource('/grubkas', grubkascontroller::class);
Route::post('/grubkas/detail', [grubkascontroller::class, 'detail'])->name('grubkas.detail');
Route::post('/grubkas/checkout', [grubkascontroller::class, 'bayar'])->name('grubkas.checkout.page');
Route::post('/grubkas/checkout/upload', [grubkascontroller::class, 'upload'])->name('grubkas.checkout.upload');
Route::post('/grubkas/checkout/confirm', [grubkascontroller::class, 'confirm'])->name('grubkas.checkout.confirm');
Route::view('/grubkas/kirim-dana', 'pages.grubkas-kirim-dana')->name('grubkas.kirim-dana.page');

// scanner
Route::get('/loginbarcode', [controllerscanner::class, 'loginbarcode'])->name('scan.login');
Route::post('/sesi/login', [controllerscanner::class, 'login']);
Route::get('/sesi/logout', [controllerscanner::class, 'logout'])->middleware('auth');
Route::middleware(['auth', 'role:scanabsen'])->group(function () {
    Route::get('/barcode', [controllerscanner::class, 'index'])->name('scan.barcode');
    Route::get('/scan/users', [tokencontroller::class, 'listUsers'])->name('scan.users');
    Route::post('/scan/users/{id}/status', [tokencontroller::class, 'updateUserStatus'])->name('scan.users.status');
    Route::post('/scan/submit', [BarcodeController::class, 'submitScan'])->name('scan.submit');
});


// jadwal
Route::get('/test', function(){
    grubkas::create([
        'Nim_key' => '2411102441203',
        'Utang_Anggota' => '10000',
    ]);
    echo 'sussess';
});

Route::get('/uy', [test::class, 'index']);
Route::get('/update', [test::class, 'updatemingguan']);


Route::get('/mqtt-test', function () {
    return view('pages.mqtt-test');
});

use Illuminate\Support\Facades\Cache;

Route::get('/mqtt-data', function () {

    return response()->json(
        Cache::get('latest_bms', [])
    );

});

// =====================
// MAKAM ADMIN PANEL
// =====================
use App\Http\Controllers\Makam\MakamAuthController;
use App\Http\Controllers\Makam\MakamNewsController;
use App\Http\Controllers\Makam\MakamTypeController;
use App\Http\Controllers\Makam\MakamOrderController;

Route::prefix('admin/makam')->group(function () {
    // Login routes (no middleware)
    Route::get('/login', [AdminAuthController::class, 'showMakamLoginForm'])->name('makam.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('makam.login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('makam.logout');

    // Protected routes
    Route::middleware('role:makam')->group(function () {
        Route::get('/', function () {
            return redirect()->route('makam.dashboard');
        })->name('makam.index');

        Route::get('/dashboard', function () {
            $totalNews = \App\Models\MakamNews::count();
            $latestNews = \App\Models\MakamNews::latest()->take(5)->get();
            $totalTypes = \App\Models\MakamType::count();
            $totalOrders = \App\Models\MakamOrder::count();
            $pendingOrders = \App\Models\MakamOrder::where('status', 'baru')->count();
            return view('makam.dashboard', compact('totalNews', 'latestNews', 'totalTypes', 'totalOrders', 'pendingOrders'));
        })->name('makam.dashboard');

        Route::get('/news', [MakamNewsController::class, 'index'])->name('makam.news.index');
        Route::get('/news/create', [MakamNewsController::class, 'create'])->name('makam.news.create');
        Route::post('/news', [MakamNewsController::class, 'store'])->name('makam.news.store');
        Route::get('/news/{id}/edit', [MakamNewsController::class, 'edit'])->name('makam.news.edit');
        Route::put('/news/{id}', [MakamNewsController::class, 'update'])->name('makam.news.update');
        Route::delete('/news/{id}', [MakamNewsController::class, 'destroy'])->name('makam.news.destroy');

        // Manajemen Jenis Makam
        Route::get('/types', [MakamTypeController::class, 'index'])->name('makam.types.index');
        Route::get('/types/create', [MakamTypeController::class, 'create'])->name('makam.types.create');
        Route::post('/types', [MakamTypeController::class, 'store'])->name('makam.types.store');
        Route::get('/types/{id}/edit', [MakamTypeController::class, 'edit'])->name('makam.types.edit');
        Route::put('/types/{id}', [MakamTypeController::class, 'update'])->name('makam.types.update');
        Route::delete('/types/{id}', [MakamTypeController::class, 'destroy'])->name('makam.types.destroy');

        // Manajemen Pesanan Makam
        Route::get('/orders', [MakamOrderController::class, 'index'])->name('makam.orders.index');
        Route::get('/orders/{id}', [MakamOrderController::class, 'show'])->name('makam.orders.show');
        Route::put('/orders/{id}/status', [MakamOrderController::class, 'updateStatus'])->name('makam.orders.status');
        Route::delete('/orders/{id}', [MakamOrderController::class, 'destroy'])->name('makam.orders.destroy');
    });
});

Route::get('/beritamakam', [MakamNewsController::class, 'AmbilData']);