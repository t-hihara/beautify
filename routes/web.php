<?php

use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::controller(TestController::class)->group(function () {
    Route::get('/admin-test', 'adminIndex')->name('test.admin.index');
    Route::get('/shop-test', 'shopIndex')->name('test.shop.index');
    Route::get('/test', 'guestIndex')->name('test.index');
    Route::get('/test_2', 'guestTransition')->name('test.transition');
});
