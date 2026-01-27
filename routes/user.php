<?php

use App\Http\Controllers\User\AuthController;
use Illuminate\Support\Facades\Route;

Route::name('user.')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/login', 'showLoginForm')->name('loginForm');
        Route::post('/login', 'login')->name('login');

        Route::middleware(['auth:user'])->group(function () {
            Route::post('/logout', 'logout')->name('logout');
        });
    });
});
