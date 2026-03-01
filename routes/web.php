<?php

use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ShopController;
use Illuminate\Support\Facades\Route;

Route::name('public.')->group(function () {
    Route::controller(HomeController::class)->group(function () {
        Route::get('/', 'index')->name('home.index');
    });
    Route::controller(ShopController::class)->group(function () {
        Route::get('/shops', 'index')->name('shops.index');
    });
});


require __DIR__ . '/admin.php';
require __DIR__ . '/shop.php';
require __DIR__ . '/user.php';
