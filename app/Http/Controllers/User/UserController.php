<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\FormUserRequest;
use App\UseCases\Auth\RegisterUserUseCase;
use App\Utilities\RecursiveCovert;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class UserController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    public function store(FormUserRequest $request, RegisterUserUseCase $useCase): RedirectResponse
    {
        try {
            $payload = RecursiveCovert::_convert($request->validated(), 'snake');
            $useCase($payload);
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', 'アカウント登録に失敗しました。');
        }

        return redirect()->route('user.verifyEmailSent');
    }
}
