<?php

use App\Http\Controllers\Manager\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/login', 'showLoginForm')->name('loginForm');
        Route::post('/login', 'login')->name('login');
    });
});
