<?php

use App\Http\Controllers\Api\V1\LocationController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/v1/locations', [LocationController::class, 'index']);
Route::get('/v1/locations/{id}', [LocationController::class, 'show']);

// Protected routes (Admin)
Route::middleware('api.key')->group(function () {
    Route::post('/v1/locations', [LocationController::class, 'store']);
});
