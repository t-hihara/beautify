<?php

use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\EmailVerificationController;
use App\Http\Controllers\User\ResetPasswordController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::name('user.')->group(function () {
    Route::middleware(['guest:user'])->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::get('/login', 'showLoginForm')->name('loginForm');
            Route::get('/login/google', 'redirectToGoogle')->name('login.google');
            Route::post('/login', 'login')->name('login');
            Route::get('/auth/google/callback', 'googleCallback')->name('login.google.callback');
        });
        Route::controller(EmailVerificationController::class)->group(function () {
            Route::prefix('verify-email')->name('verifyEmail.')->group(function () {
                Route::get('/', 'verifyEmailSent')->name('sent');
                Route::get('/failed', 'verifyEmailFailed')->name('failed');
            });
            Route::get('/verify-email/{id}/{hash}', 'verifyEmail')->name('verifyEmail')->middleware(['signed']);
        });
        Route::controller(ResetPasswordController::class)->group(function () {
            Route::get('/forget-password', 'showForgotForm')->name('password.request');
            Route::post('/forgot-password', 'sendResetLink')->name('password.email');
            Route::get('/forgot-password/sent', 'forgotPasswordSent')->name('password.sent');
            Route::get('/reset-password/{token}', 'showResetForm')->name('password.reset');
            Route::post('/reset-password', 'reset')->name('password.update');
        });
        Route::controller(UserController::class)->group(function () {
            Route::get('/register', 'create')->name('create');
            Route::post('/register', 'store')->name('store');
        });
    });
    Route::middleware(['auth:user'])->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::post('/logout', 'logout')->name('logout');
        });
        Route::controller(EmailVerificationController::class)->group(function () {
            Route::get('/verify-email/success', 'verifyEmailSuccess')->name('verifyEmail.success');
        });
    });
});
