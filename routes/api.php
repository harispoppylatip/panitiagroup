<?php

use App\Http\Controllers\GrubkasController;
use App\Http\Controllers\TugasController;
use Illuminate\Support\Facades\Route;

Route::post('/midtrans-callback', [GrubkasController::class, 'callback']);
Route::middleware('whatsapp_auth')->group(function() {
    Route::post('/tugas/store', [TugasController::class, 'storeapi']);
    Route::post('/tugas', [TugasController::class, 'gettugasapi']);
});