<?php

use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\Api\TugasApiController;
use Illuminate\Support\Facades\Route;

Route::post('/testapi', [WebhookController::class, 'callback']);

Route::middleware('whatsapp_auth')->group(function () {
    Route::get('/tugas', [TugasApiController::class, 'index']);
    Route::post('/tugas', [TugasApiController::class, 'simpan']);
    Route::post('/tugas/hapus', [TugasApiController::class, 'hapus']);

    // path lama bot WhatsApp, tetap jalan
    Route::post('/tugas/store', [TugasApiController::class, 'simpan']);
    Route::post('/tugas/edit', [TugasApiController::class, 'simpan']);
});
