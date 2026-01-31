<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\UseCases\Auth\VerifyEmailUseCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EmailVerificationController extends Controller
{
    public function verifyEmailSent(): Response
    {
        return Inertia::render('Auth/VerifyEmailSent');
    }

    public function verifyEmailSuccess(): Response
    {
        return Inertia::render('Auth/VerifyEmailSuccess');
    }

    public function verifyEmailFailed(): Response
    {
        return Inertia::render('Auth/VerifyEmailFailed');
    }

    public function verifyEmail(Request $request, VerifyEmailUseCase $useCase): RedirectResponse
    {
        $result = $useCase($request->route('id'), $request->route('hash'));

        return $result['success']
            ? redirect()->route('user.verifyEmail.success')
            : redirect()->route('user.verifyEmail.failed');
    }
}
