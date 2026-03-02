<?php

namespace App\UseCases\User\Auth;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Password;

class SendPasswordResetLinkUseCase
{
    public function __invoke(array $data): string
    {
        $email = $data['email'];
        $user = $this->queryWithFilters($email)->first();

        if (!$user || !$user->hasRole('user', 'user')) {
            return Password::INVALID_USER;
        }

        return Password::sendResetLink(['email' => $email]);
    }

    private function queryWithFilters(?string $email): Builder
    {
        return User::query()
            ->when($email, fn(Builder $q, $e) => $q->where('email', $e));
    }
}
