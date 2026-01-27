<?php

use App\Http\Controllers\User\AuthController;
use Illuminate\Support\Facades\Route;

Route::name('user.')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/login', 'showLoginForm')->name('loginForm');
        Route::get('/login/google', 'redirectToGoogle')->name('login.google');
        Route::post('/login', 'login')->name('login');
        Route::post('/login/google', 'googleCallback')->name('login.google.callback');

        Route::middleware(['auth:user'])->group(function () {
            Route::post('/logout', 'logout')->name('logout');
        });
    });
});
