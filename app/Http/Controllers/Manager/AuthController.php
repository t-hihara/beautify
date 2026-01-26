<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
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

    public function guard()
    {
        return auth()->guard(match (request()->segment(1)) {
            'admin' => 'admin',
            'shop'  => 'shop',
            default => 'web',
        });
    }

    public function redirectTo(): string
    {
        return match ($this->guard()->getName()) {
            'admin' => route('admin.dashboard'),
            'shop'  => route('shop.dashboard'),
            default => '/',
        };
    }
}
