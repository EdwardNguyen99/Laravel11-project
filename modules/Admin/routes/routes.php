<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\src\Http\Controllers\DashboardController;
use Modules\Admin\src\Http\Controllers\LoginController;

Route::middleware('web')->prefix('admin')->name('admin.')->group(function () {
    // Guest routes
    Route::middleware('guest')->group(function () {
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
    });

    // Protected routes (admin only)
    Route::middleware('admin')->group(function () {
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    });
});
