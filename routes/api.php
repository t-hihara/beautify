<?php

use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\ShopController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->controller(ShopController::class)->group(function () {
    Route::get('/shopsForSearch', 'list')->name('shops.list');
});

Route::middleware(['auth:sanctum'])->controller(MenuController::class)->group(function () {
    Route::get('/shops/{shop}/menus', 'options')->name('shops.menus');
});
