<?php

use App\Http\Controllers\GrubkasController;
use App\Http\Controllers\test;
use App\Http\Controllers\TugasController;
use Illuminate\Support\Facades\Route;

Route::post('/testapi', [test::class, 'callback']);
Route::middleware('whatsapp_auth')->group(function() {
    Route::post('/tugas/store', [TugasController::class, 'storeapi']);
    Route::post('/tugas/edit', [TugasController::class, 'edittugasapi']);
    Route::post('/tugas/hapus', [TugasController::class, 'deletetugasapi']);
    Route::get('/tugas', [TugasController::class, 'gettugasapi']);
    
    
    Route::get('/grupkasapi', [GrubkasController::class, 'grubkasinfoapi']);
}); 