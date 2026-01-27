<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    protected string $redirectTo = '/';

    public function showLoginForm(): Response
    {
        return Inertia::render('Auth/Login');
    }

    public function guard(): StatefulGuard
    {
        return auth()->guard('user');
    }

    public function redirectTo(): string
    {
        return $this->redirectTo();
    }

    public function loggedOut(Request $request): RedirectResponse
    {
        return redirect()->route('user.loginForm');
    }
}
