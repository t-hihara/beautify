<?php

use App\Http\Controllers\Manager\AuthController;
use App\Http\Controllers\Manager\ShopController;
use App\Http\Controllers\Manager\ShopDashboardController;
use App\Http\Controllers\Manager\ShopProfileController;
use App\Http\Controllers\Manager\ShopStaffController;
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

        Route::controller(ShopProfileController::class)->group(function () {
            Route::middleware(['permission:view.shops'])->group(function () {
                Route::get('/profile', 'index')->name('index');
                Route::get('/profile/staffs', 'staffs')->name('staff');
            });
        });

        Route::prefix('staffs')->name('staffs.')->controller(ShopStaffController::class)->group(function () {
            Route::middleware(['permission:view.staffs'])->group(function () {
                Route::get('/', 'index')->name('index');
            });
            Route::middleware(['permission:export.staffs'])->group(function () {
                Route::get('/export/excel', 'exportExcel')->name('excel');
                Route::get('/export/csv', 'exportCsv')->name('csv');
            });
        });
    });
});
