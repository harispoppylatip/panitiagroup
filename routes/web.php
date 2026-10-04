<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;

// Admin Controllers
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\BerandaController;
use App\Http\Controllers\Admin\TimController;
use App\Http\Controllers\Admin\TokenController;
use App\Http\Controllers\Admin\TugasController;
use App\Http\Controllers\Admin\FinanceController;

// Scan Controllers
use App\Http\Controllers\Scan\ScanController;
use App\Http\Controllers\Scan\BarcodeController;

// Public Controllers
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\GrubkasController;
use App\Http\Controllers\Public\GalleryController;

// Admin Gallery Controller
use App\Http\Controllers\Admin\AdminGalleryController;

// API Controllers
use App\Http\Controllers\Api\ScheduleController;

// =====================
// AUTH ROUTES
// =====================
Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// =====================
// ADMIN PANEL
// =====================
Route::middleware('auth')->prefix('admin')->group(function () {

    Route::middleware('role:admin,akuntan,anggota')->group(function () {
        // Management Tugas
        Route::get('/tugas', [TugasController::class, 'index'])->name('admin.tugas.index');
        Route::get('/tugas/create', [TugasController::class, 'create'])->name('admin.tugas.create');
        Route::post('/tugas/createnew', [TugasController::class, 'postnew'])->name('admin.tugas.createnew');
        Route::get('/tugas/{id}', [TugasController::class, 'show'])->name('admin.tugas.show');
        Route::get('/tugas/{id}/edit', [TugasController::class, 'edit'])->name('admin.tugas.edit');
        Route::put('/tugas/{id}/edit/now', [TugasController::class, 'update'])->name('admin.tugas.editsend');
        Route::delete('/tugas/delete/{id}', [TugasController::class, 'destroy'])->name('admin.tugas.delete');

        // Management Beranda (hanya foto hero)
        Route::get('/beranda', [BerandaController::class, 'index'])->name('admin.beranda.index');
        Route::get('/beranda/hero/edit', [BerandaController::class, 'editHero'])->name('admin.beranda.edit-hero');
        Route::put('/beranda/hero/update', [BerandaController::class, 'updateHero'])->name('admin.beranda.update-hero');

        // Galeri (Google Drive)
        Route::get('/galeri', [AdminGalleryController::class, 'index'])->name('admin.galeri.index');
        Route::post('/galeri/sync', [AdminGalleryController::class, 'sync'])->name('admin.galeri.sync');
    });

    Route::middleware('role:admin,akuntan')->group(function () {
        // Management Tim (satu halaman: data anggota + token absen + tampilan beranda + kas)
        Route::get('/tim', [TimController::class, 'index'])->name('admin.tim.index');
        Route::post('/tim', [TimController::class, 'store'])->name('admin.tim.store');
        Route::put('/tim/{id}', [TimController::class, 'update'])->name('admin.tim.update');
        Route::delete('/tim/{id}', [TimController::class, 'destroy'])->name('admin.tim.destroy');
        Route::post('/tim/refresh-all', [TimController::class, 'refreshAllTokens'])->name('admin.tim.refresh-all');

        // Finance
        Route::get('/finance', [FinanceController::class, 'index'])->name('admin.finance.index');
        Route::post('/finance/settings', [FinanceController::class, 'updateSettings'])->name('admin.finance.settings.update');
        Route::post('/finance/manual-cash', [FinanceController::class, 'storeManualCash'])->name('admin.finance.manual-cash.store');
        Route::post('/finance/manual-debt', [FinanceController::class, 'storeManualDebt'])->name('admin.finance.manual-debt.store');
        Route::post('/finance/{nim}/approve', [FinanceController::class, 'approvePayment'])->name('admin.finance.payment.approve');
        Route::post('/finance/{nim}/reject', [FinanceController::class, 'rejectPayment'])->name('admin.finance.payment.reject');
        Route::middleware('role:admin')->group(function () {
            Route::post('/finance/reset', [FinanceController::class, 'resetAll'])->name('admin.finance.reset');
        });

        // Setting akun scan
        Route::get('/scan-login-setting', [AdminController::class, 'scanLoginSetting'])->name('admin.scan.login.setting');
        Route::post('/scan-login-setting', [AdminController::class, 'updateScanLoginSetting'])->name('admin.scan.login.setting.update');

        // Management User
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

// =====================
// PUBLIC PAGES
// =====================
Route::get('/', [HomeController::class, 'home']);
Route::get('/jadwal', [ScheduleController::class, 'index'])->name('jadwal');
Route::get('/tugas', [TugasController::class, 'front'])->name('tugas');

// Galeri Publik
Route::get('/galeri', [GalleryController::class, 'index'])->name('galeri.index');
Route::get('/galeri/foto/{fileId}', [GalleryController::class, 'photo'])->name('galeri.photo');
Route::get('/galeri/video/{fileId}', [GalleryController::class, 'video'])->name('galeri.video');

// Pembayaran Grubkas
Route::resource('/grubkas', GrubkasController::class)->only(['index']);
Route::post('/grubkas/detail', [GrubkasController::class, 'detail'])->name('grubkas.detail');
Route::post('/grubkas/checkout', [GrubkasController::class, 'bayar'])->name('grubkas.checkout.page');
Route::post('/grubkas/checkout/upload', [GrubkasController::class, 'upload'])->name('grubkas.checkout.upload');
Route::post('/grubkas/checkout/confirm', [GrubkasController::class, 'confirm'])->name('grubkas.checkout.confirm');

// =====================
// SCAN / ABSENSI
// =====================
Route::get('/loginbarcode', [ScanController::class, 'loginbarcode'])->name('scan.login');
Route::post('/sesi/login', [ScanController::class, 'login']);
Route::get('/sesi/logout', [ScanController::class, 'logout'])->middleware('auth');

Route::middleware(['auth', 'role:scanabsen'])->group(function () {
    Route::get('/barcode', [ScanController::class, 'index'])->name('scan.barcode');
    Route::get('/scan/users', [TokenController::class, 'listUsers'])->name('scan.users');
    Route::post('/scan/users/{id}/status', [TokenController::class, 'updateUserStatus'])->name('scan.users.status');
    Route::post('/scan/submit', [BarcodeController::class, 'submitScan'])->name('scan.submit');
});

// =====================
// UTILITY ROUTES
// =====================
Route::get('/mqtt-test', function () {
    return view('pages.mqtt-test');
});

Route::get('/mqtt-data', function () {
    return response()->json(
        Cache::get('latest_bms', [])
    );
});
