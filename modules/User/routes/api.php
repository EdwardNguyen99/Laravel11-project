<?php

use Illuminate\Support\Facades\Route;
use Modules\User\src\Http\Controllers\Api\AuthController;
use Modules\User\src\Http\Controllers\Api\UserController;

// Public routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logout-all', [AuthController::class, 'logoutAll']);
    });

    // User CRUD routes (admin only)
    Route::apiResource('users', UserController::class);
});
