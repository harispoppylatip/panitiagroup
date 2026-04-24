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

Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
Route::redirect('/upload', '/admin/upload');

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/upload', [AdminController::class, 'upload'])->name('admin.upload');
    Route::post('/upload', [indexcontroller::class, 'upload'])->name('admin.upload.store');
    Route::get('/editor', [controllerpost::class, 'editor'])->name('admin.editor');

    // management tugas
    Route::get('/tugas', [TugasController::class, 'index'])->name('admin.tugas.index');
    Route::get('/tugas/create', [TugasController::class, 'create'])->name('admin.tugas.create');
    Route::post('/tugas/createnew', [TugasController::class, 'postnew'])->name('admin.tugas.createnew');
    Route::get('/tugas/{id}', [TugasController::class, 'show'])->name('admin.tugas.show');
    Route::get('/tugas/{id}/edit', [TugasController::class, 'edit'])->name('admin.tugas.edit');
    Route::put('/tugas/{id}/edit/now', [TugasController::class, 'update'])->name('admin.tugas.editsend');
    Route::delete('/tugas/delete/{id}', [TugasController::class, 'destroy'])->name('admin.tugas.delete');
    
    
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

    // management beranda
    Route::get('/beranda', [BerandaController::class, 'index'])->name('admin.beranda.index');
    Route::get('/beranda/hero/edit', [BerandaController::class, 'editHero'])->name('admin.beranda.edit-hero');
    Route::put('/beranda/hero/update', [BerandaController::class, 'updateHero'])->name('admin.beranda.update-hero');
    Route::get('/beranda/team/edit', [BerandaController::class, 'editTeam'])->name('admin.beranda.edit-team');
    Route::post('/beranda/team/store', [BerandaController::class, 'storeTeam'])->name('admin.beranda.store-team');
    Route::put('/beranda/team/{teamMember}/update', [BerandaController::class, 'updateTeam'])->name('admin.beranda.update-team');
    Route::delete('/beranda/team/{teamMember}', [BerandaController::class, 'destroyTeam'])->name('admin.beranda.destroy-team');
});

// pages
Route::get('/', [controllerpost::class, 'home']);
Route::get('/jadwal', [ambiljadwalapi::class, 'index'])->name('jadwal');
Route::get('/tugas', [TugasController::class, 'front'])->name('tugas');
Route::get('/grubkas', function() {
    return view('pages.grubkas');
})->name('grubkas');
Route::delete('/edit/del/{id}', [controllerpost::class, 'delete']);

// scanner
Route::get('/loginbarcode', [controllerscanner::class, 'loginbarcode'])->name('scan.login');
Route::post('/sesi/login', [controllerscanner::class, 'login']);
Route::get('/sesi/logout', [controllerscanner::class, 'logout'])->middleware('auth');
Route::get('/barcode', [controllerscanner::class, 'index'])->middleware('auth')->name('scan.barcode');
Route::get('/scan/users', [tokencontroller::class, 'listUsers'])->middleware('auth')->name('scan.users');
Route::post('/scan/users/{id}/status', [tokencontroller::class, 'updateUserStatus'])->middleware('auth')->name('scan.users.status');
Route::post('/scan/submit', [BarcodeController::class, 'submitScan'])->middleware('auth')->name('scan.submit');


// jadwal
Route::get('/wee', [tokencontroller::class, 'refreshtoken']);