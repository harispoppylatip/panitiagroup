<?php

use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\Api\TugasApiController;
use Illuminate\Support\Facades\Route;

Route::post('/testapi', [WebhookController::class, 'callback']);

Route::middleware('whatsapp_auth')->group(function () {
    Route::post('/tugas/store', [TugasApiController::class, 'storeapi']);
    Route::post('/tugas/edit', [TugasApiController::class, 'edittugasapi']);
    Route::post('/tugas/hapus', [TugasApiController::class, 'deletetugasapi']);
    Route::get('/tugas', [TugasApiController::class, 'gettugasapi']);
});
