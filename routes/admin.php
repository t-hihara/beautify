<?php

use App\Http\Controllers\Manager\ActivityLogController;
use App\Http\Controllers\Manager\AdminDashboardController;
use App\Http\Controllers\Manager\AuthController;
use App\Http\Controllers\Public\ShopController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['guest:admin'])->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::get('/login', 'showLoginForm')->name('loginForm');
            Route::post('/login', 'login')->name('login');
        });
    });

    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::prefix('shops')->name('shops.')->controller(ShopController::class)->group(function () {
            Route::get('/', 'index')->name('index');
        });
        Route::prefix('logs')->name('logs.')->controller(ActivityLogController::class)->group(function () {
            Route::get('/', 'index')->name('index');
        });
    });
});
