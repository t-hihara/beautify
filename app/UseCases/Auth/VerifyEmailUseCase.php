<?php

namespace App\UseCases\Auth;

use App\Models\User;

class VerifyEmailUseCase
{
    public function __invoke(int|string $id, string $hash): array
    {
        $user = User::find($id);

        if ($user === null) {
            return ['success' => false];
        }

        if (!hash_equals($hash, sha1($user->getEmailForVerification()))) {
            return ['success' => false];
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        auth()->guard('user')->login($user);

        return ['success' => true];
    }
}
