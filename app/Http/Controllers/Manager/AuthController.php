<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
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

    public function guard(): string
    {
        return auth()->guard(match (request()->segment(1)) {
            'admin' => 'admin',
            'shop'  => 'shop',
            default => 'web',
        });
    }

    public function redirectTo(): string
    {
        $guardName = request()->segment(1) ?? 'web';

        return match ($guardName) {
            'admin' => route('admin.dashboard'),
            'shop'  => route('shop.dashboard'),
            default => '/',
        };
    }

    protected function attemptLogin(Request $request): bool
    {
        $credentials = $this->credentials($request);
        $guard       = $this->guard();
        $guardName   = $request->segment(1) ?? 'web';
        $user        = User::where('email', $credentials['email'])->first();

        if (!$user || !$this->hasValidRole($user, $guardName)) {
            return false;
        }

        return $guard->attempt($credentials, $request->filled('remember'));
    }

    protected function loggedOut(Request $request): RedirectResponse
    {
        $guardName = $request->segment(1) ?? 'web';

        return match ($guardName) {
            'admin' => redirect()->route('admin.loginForm'),
            'shop'  => redirect()->route('shop.loginForm'),
            default => redirect('/'),
        };
    }

    private function hasValidRole(User $user, string $guard): bool
    {
        return match ($guard) {
            'admin' => $user->hasRole('admin', 'admin'),
            'shop'  => $user->hasAnyRole(['staff_owner', 'staff'], 'shop'),
            default => false,
        };
    }
}
