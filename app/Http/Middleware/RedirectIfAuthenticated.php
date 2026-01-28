<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    private const GUARD_REDIRECT = [
        'user'  => 'home.index',
        'admin' => 'admin.dashboard',
        'shop'  => 'shop.dashboard',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        foreach (self::GUARD_REDIRECT as $guard => $route) {
            if (auth()->guard($guard)->check()) {
                return redirect()->route($route);
            }
        }

        return $next($request);
    }
}
