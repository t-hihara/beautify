<?php

use App\Http\Controllers\Api\ShopController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->controller(ShopController::class)->group(function () {
    Route::get('/shopsForSearch', 'list')->name('shops.list');
});
