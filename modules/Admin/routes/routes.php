<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\src\Http\Controllers\DashboardController;
use Modules\Admin\src\Http\Controllers\LoginController;

Route::middleware('web')->prefix('admin')->name('admin.')->group(function () {
    // Auth routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Protected routes
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});
