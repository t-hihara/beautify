<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\UseCases\Auth\UserGoogleLoginUseCase;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse as HttpRedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse as HttpFoundationRedirectResponse;
use Throwable;

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

    public function googleCallback(UserGoogleLoginUseCase $useCase): HttpRedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $result     = $useCase->execute($googleUser);
            \Log::info($result);
            if (!$result['success']) {
                return redirect()->route('user.loginForm')
                    ->withErrors(['email' => $result['error']]);
            }

            return redirect($this->redirectTo);
        } catch (Throwable $e) {
            report($e);
            \Log::error($e->getMessage());
            return redirect()->route('user.loginForm')
                ->withErrors(['email' => 'Googleログインに失敗しました。']);
        }
    }

    protected function loggedOut(Request $request): HttpRedirectResponse
    {
        return redirect()->route('user.loginForm');
    }
}
