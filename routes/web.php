<?php

use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ShopController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home.index');
});
Route::controller(ShopController::class)->group(function () {
    Route::get('/shops', 'index')->name('shops.index');
});

Route::controller(TestController::class)->group(function () {
    Route::get('/admin-test', 'adminIndex')->name('test.admin.index');
    Route::get('/shop-test', 'shopIndex')->name('test.shop.index');
    Route::get('/test', 'guestIndex')->name('test.index');
    Route::get('/test_2', 'guestTransition')->name('test.transition');
});
