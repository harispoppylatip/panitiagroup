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

Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
Route::redirect('/upload', '/admin/upload');

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/upload', [AdminController::class, 'upload'])->name('admin.upload');
    Route::post('/upload', [indexcontroller::class, 'upload'])->name('admin.upload.store');
    Route::get('/editor', [controllerpost::class, 'editor'])->name('admin.editor');

    // management tugas (dummy UI)
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
});

// pages
Route::get('/', [controllerpost::class, 'home']);
Route::get('/jadwal', [ambiljadwalapi::class, 'index'])->name('jadwal');
Route::get('/tugas', [TugasController::class, 'front'])->name('tugas');


// gambar funcation
Route::get('/galeri', [indexcontroller::class, 'ambilgambar'])->name('gambare');
Route::delete('/galeri/hapus/{id}', [indexcontroller::class, 'hapusgambar'])->name('hapusgambar');

Route::get('/post', [controllerpost::class, 'index']);
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
Route::get('/test', [ambiljadwalapi::class, 'index']);