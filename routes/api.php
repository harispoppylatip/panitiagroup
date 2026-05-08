<?php

use App\Http\Controllers\GrubkasController;
use App\Http\Controllers\TugasController;
use Illuminate\Support\Facades\Route;

Route::post('/midtrans-callback', [GrubkasController::class, 'callback']);
Route::post('/tugas', [TugasController::class, 'storeapi']);