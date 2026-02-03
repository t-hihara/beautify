<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\FormForgotPasswordRequest;
use App\Http\Requests\User\FormResetPasswordRequest;
use App\UseCases\Auth\ResetPasswordUseCase;
use App\UseCases\Auth\SendPasswordResetLinkUseCase;
use App\Utilities\RecursiveCovert;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;
use Inertia\Response;

class ResetPasswordController extends Controller
{
    public function showForgotForm(): Response
    {
        return Inertia::render('Auth/ForgotPassword');
    }

    public function sendResetLink(
        FormForgotPasswordRequest $request,
        SendPasswordResetLinkUseCase $useCase
    ): RedirectResponse {
        $data   = RecursiveCovert::_convert($request->validated(), 'snake');
        $status = $useCase($data);

        if ($status === Password::RESET_THROTTLED) {
            return back()->withErrors(['email' => __($status)]);
        }

        if ($status === Password::INVALID_USER) {
            return back()->withErrors(['email' => 'このメールアドレスは登録されていません。']);
        }

        return redirect()->route('user.password.sent');
    }

    public function forgotPasswordSent(): Response
    {
        return Inertia::render('Auth/ForgotPasswordSent');
    }

    public function showResetForm(Request $request, string $token): Response
    {
        return Inertia::render('Auth/ResetPassword', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function reset(FormResetPasswordRequest $request, ResetPasswordUseCase $useCase): RedirectResponse
    {
        $data   = RecursiveCovert::_convert($request->validated(), 'snake');
        $status = $useCase($data);

        if ($status === Password::INVALID_TOKEN) {
            return back()->withErrors(['password' => 'このリンクは無効または期限切れです。']);
        }

        if ($status === Password::INVALID_USER) {
            return back()->withErrors(['password' => 'このメールアドレスは登録されていません。']);
        }

        return redirect()->route('user.loginForm')
            ->with('success', 'パスワードを変更しました。');
    }
}
