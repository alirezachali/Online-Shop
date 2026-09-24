<?php

use App\Http\Controllers\Api\CatalogIngestController;
use App\Http\Controllers\Api\CustomerDirectoryController;
use App\Http\Controllers\Api\OutboxAckController;
use App\Http\Controllers\Api\OutboxPullController;
use App\Http\Middleware\VerifyIntegrationToken;
use Illuminate\Support\Facades\Route;

Route::middleware(VerifyIntegrationToken::class)->group(function () {
    Route::post('/catalog/ingest', CatalogIngestController::class);
    Route::get('/outbox/pending', OutboxPullController::class);
    Route::post('/outbox/ack', OutboxAckController::class);
    Route::get('/customers', CustomerDirectoryController::class);
});
