<?php

use App\Http\Controllers\Api\CatalogIngestController;
use App\Http\Middleware\VerifyIntegrationToken;
use Illuminate\Support\Facades\Route;

Route::middleware(VerifyIntegrationToken::class)->group(function () {
    Route::post('/catalog/ingest', CatalogIngestController::class);
});
