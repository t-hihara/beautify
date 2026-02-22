<?php

namespace App\Http\Controllers\Manager\Shop;

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

    public function showLoginForm(): Response
    {
        return Inertia::render('Auth/Login');
    }

    public function guard(): StatefulGuard
    {
        return auth()->guard('shop');
    }

    public function redirectTo(): string
    {
        return route('shop.dashboard');
    }

    protected function attemptLogin(Request $request): bool
    {
        $credentials = $this->credentials($request);
        $user        = User::where('email', $credentials['email'])->first();

        if (!$user || !$user->hasRole(['staff_owner', 'staff'], 'shop')) {
            return false;
        }

        return $this->guard()->attempt($credentials, $request->filled('remember'));
    }

    protected function loggedOut(Request $request): RedirectResponse
    {
        return redirect()->route('shop.loginForm');
    }
}
