<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;

class AuthenticateGuard extends Authenticate
{
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        if ($request->is('admin/*')) {
            return route('admin.loginForm');
        }
        if ($request->is('shop/*')) {
            return route('shop.loginForm');
        }

        return route('home.index');
    }
}
