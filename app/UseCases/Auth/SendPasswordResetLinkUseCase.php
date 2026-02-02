<?php

namespace App\UseCases\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Password;

class SendPasswordResetLinkUseCase
{
    public function __invoke(array $data): string
    {
        $email = $data['email'];
        $user = User::byEmail($email)->first();

        if (!$user || !$user->hasRole('user', 'user')) {
            return Password::INVALID_USER;
        }

        return Password::sendResetLink(['email' => $email]);
    }
}
