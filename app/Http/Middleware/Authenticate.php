<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->exceptsJson()) {
            if ($request->is('admin/*')) {
                if (!auth()->guard('admin')->check()) {
                    abort(403, 'このページにアクセスする権限がありません');
                }
            } elseif ($request->is('shop/*')) {
                if (!auth()->guard('shop')->check()) {
                    abort(403, 'このページにアクセスする権限がありません');
                }
            }
        }
        return $next($request);
    }
}
