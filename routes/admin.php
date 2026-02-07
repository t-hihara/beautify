<?php

use App\Http\Controllers\Manager\ActivityLogController;
use App\Http\Controllers\Manager\AdminDashboardController;
use App\Http\Controllers\Manager\AuthController;
use App\Http\Controllers\Manager\ExportFileController;
use App\Http\Controllers\Manager\ShopController;
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
            Route::middleware(['permission:view.shops'])->get('/', 'index')->name('index');
            Route::middleware(['permission:export.shops'])->group(function () {
                Route::get('/export/excel', 'exportExcel')->name('excel');
                Route::get('/export/csv', 'exportCsv')->name('csv');
            });
        });
        Route::prefix('logs')->name('logs.')->controller(ActivityLogController::class)->group(function () {
            Route::middleware('permission:view.logs')->get('/', 'index')->name('index');
        });
        Route::prefix('exports')->name('exports.')->controller(ExportFileController::class)->group(function () {
            Route::middleware('permission:view.exports')->get('/', 'index')->name('index');
            Route::middleware('permission:manage.exports')->group(function () {
                Route::get('/download/{exportFile}', 'download')->name('download');
            });
        });
    });
});
