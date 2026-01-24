<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    public function rootView(Request $request): string
    {
        if ($request->is('admin/*') || $request->is('shop/*')) {
            return 'app-manager';
        }

        return 'app-user';
    }

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $guard = $this->getGuard();
        $user  = request()->user($guard);

        return [
            ...parent::share($request),
            'guard' => $guard,
            'auth'  => [
                'user' => $user,
            ],
            'flash' => [
                'success' => $request->session()->pull('success'),
                'error'   => $request->session()->pull('error'),
                'warning' => $request->session()->pull('warning'),
            ],
        ];
    }

    private function getGuard(): string
    {
        $firstSegment = request()->segment(1);

        return match ($firstSegment) {
            'admin'        => 'admin',
            'shop'         => 'shop',
            'user', null   => 'user',
            default        => 'web',
        };
    }
}
