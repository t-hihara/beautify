<?php

namespace App\Http\Controllers\User;

use App\Enum\ActiveFlagTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse as HttpRedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse as HttpFoundationRedirectResponse;

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

    public function redirectToGoogle(): HttpFoundationRedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback(): HttpRedirectResponse
    {
        $googleUser = Socialite::driver('google')->user();
        $email      = $googleUser->getEmail();
        $user       = User::where('email', $email)->first();

        if (!$user) {
            $user = User::create([
                'last_name'         => $googleUser->user['family_name'] ?? '',
                'first_name'        => $googleUser->user['given_name'] ?? '',
                'email'             => $googleUser->email,
                'email_verified_at' => now(),
                'google_id'         => $googleUser->id,
                'active_flag'       => ActiveFlagTypeEnum::ACTIVE,
            ]);
            $user->assignRole('user', 'user');
        } else {
            if (!$user->hasRole('user', 'user')) {
                return redirect()->route('user.loginForm')->with('error', 'このアカウントではログインできません。');
            }
        }

        auth()->guard('user')->login($user, false);
        return redirect($this->redirectTo);
    }

    protected function loggedOut(Request $request): HttpRedirectResponse
    {
        return redirect()->route('user.loginForm');
    }
}
