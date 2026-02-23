<?php

use App\Http\Controllers\Manager\Admin\ActivityLogController;
use App\Http\Controllers\Manager\Admin\MenuController;
use App\Http\Controllers\Manager\Admin\ShopController;
use App\Http\Controllers\Manager\Admin\ShopStaffController;
use App\Http\Controllers\Manager\Admin\AdminDashboardController;
use App\Http\Controllers\Manager\Admin\AuthController;
use App\Http\Controllers\Manager\Admin\PlanController;
use App\Http\Controllers\Manager\ExportFileController;
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
            Route::middleware(['permission:manage.shops'])->group(function () {
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{shop}/edit', 'edit')->name('edit');
                Route::patch('/{shop}', 'update')->name('update');
                Route::delete('/{shop}', 'destroy')->name('delete');
            });
            Route::middleware(['permission:export.shops'])->group(function () {
                Route::get('/export/excel', 'exportExcel')->name('excel');
                Route::get('/export/csv', 'exportCsv')->name('csv');
            });
            Route::middleware(['permission:view.shops'])->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{shop}', 'show')->name('show');
                Route::get('/{shop}/staffs', 'staffs')->name('staff');
                Route::get('/{shop}/plans', 'plans')->name('plans');
            });
        });

        Route::prefix('staffs')->name('staffs.')->controller(ShopStaffController::class)->group(function () {
            Route::middleware(['permission:view.staffs'])->get('/', 'index')->name('index');
            Route::middleware(['permission:manage.staffs'])->group(function () {
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{staff}/edit', 'edit')->name('edit');
                Route::patch('/{staff}', 'update')->name('update');
                Route::delete('/{staff}', 'destroy')->name('delete');
            });
            Route::middleware(['permission:export.staffs'])->group(function () {
                Route::get('/export/excel', 'exportExcel')->name('excel');
                Route::get('/export/csv', 'exportCsv')->name('csv');
            });
        });

        Route::prefix('menus')->name('menus.')->controller(MenuController::class)->group(function () {
            Route::middleware(['permission:view.menus'])->group(function () {
                Route::get('/', 'index')->name('index');
            });
            Route::middleware(['permission:manage.menus'])->group(function () {
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{menu}/edit', 'edit')->name('edit');
                Route::patch('/{menu}', 'update')->name('update');
                Route::delete('/{menu}', 'destroy')->name('delete');
            });
            Route::middleware(['permission:export.menus'])->group(function () {
                Route::get('/export/excel', 'exportExcel')->name('excel');
                Route::get('/export/csv', 'exportCsv')->name('csv');
            });
        });

        Route::prefix('plans')->name('plans.')->controller(PlanController::class)->group(function () {
            Route::middleware(['permission:view.plans'])->group(function () {
                Route::get('/', 'index')->name('index');
            });
            Route::middleware(['permission:export.plans'])->group(function () {
                Route::get('/export/excel', 'exportExcel')->name('excel');
                Route::get('/export/csv', 'exportCsv')->name('csv');
            });
        });

        Route::prefix('exports')->name('exports.')->controller(ExportFileController::class)->group(function () {
            Route::middleware('permission:view.exports')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/unloaded-count', 'unloadedCount')->name('unloaded-count');
            });
            Route::middleware('permission:manage.exports')->group(function () {
                Route::get('/download/{exportFile}', 'download')->name('download');
                Route::delete('/download/{exportFile}', 'destroy')->name('delete');
            });
        });

        Route::prefix('logs')->name('logs.')->controller(ActivityLogController::class)->group(function () {
            Route::middleware('permission:view.logs')->get('/', 'index')->name('index');
        });
    });
});
