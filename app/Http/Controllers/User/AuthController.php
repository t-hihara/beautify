<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
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
        return $this->redirectTo;
    }

    protected function attemptLogin(Request $request): bool
    {
        $credentials = $this->credentials($request);
        $guard       = $this->guard();
        $user        = User::where('email', $credentials['email'])->first();

        if (!$user || !$user->hasRole('user', 'user')) {
            return false;
        }

        return $guard->attempt($credentials, $request->filled('remember'));
    }

    protected function loggedOut(Request $request): RedirectResponse
    {
        return redirect()->route('user.loginForm');
    }
}
