<?php 
use Illuminate\Support\Facades\Route;
use Modules\User\src\Http\Controllers\UserController;

// Module User
Route::group(['namespace' => 'Modules\User\src\Http\Controllers'], function () {
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user.index');
    });
});