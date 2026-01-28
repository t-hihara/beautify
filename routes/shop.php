<?php

use App\Http\Controllers\Manager\AuthController;
use App\Http\Controllers\Manager\ShopDashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('shop')->name('shop.')->group(function () {
    Route::middleware(['guest:shop'])->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::get('/login', 'showLoginForm')->name('loginForm');
            Route::post('/login', 'login')->name('login');
        });
    });

    Route::middleware(['auth:shop'])->group(function () {
        Route::get('/dashboard', [ShopDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});
