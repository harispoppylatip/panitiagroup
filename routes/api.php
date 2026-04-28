<?php

use App\Http\Controllers\GrubkasController;
use Illuminate\Support\Facades\Route;

Route::post('/midtrans-callback', [GrubkasController::class, 'callback']);