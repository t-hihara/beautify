<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateGuard
{
    public function handle(Request $request, Closure $next, string $guard): Response
    {
        if (!auth()->guard($guard)->check()) {
            return match ($guard) {
                'admin' => redirect()->route('admin.loginForm'),
                'shop'  => redirect()->route('shop.loginForm'),
                'user'  => redirect()->route('home.index'),
                default => redirect()->route('home.index'),
            };
        }
        return $next($request);
    }
}
