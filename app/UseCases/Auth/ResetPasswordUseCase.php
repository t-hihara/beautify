<?php

namespace App\UseCases\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ResetPasswordUseCase
{
    public function __invoke(array $data): string
    {
        $credentials = [
            'email' => $data['email'],
            'token' => $data['token'],
            'password' => $data['password'],
            'password_confirmation' => $data['password_confirmation'],
        ];

        return Password::reset($credentials, function ($user, $password): void {
            $user->forceFill([
                'password' => Hash::make($password),
            ])->save();
        });
    }
}
