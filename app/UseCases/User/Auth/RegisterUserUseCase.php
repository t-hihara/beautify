<?php

namespace App\UseCases\User\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RegisterUserUseCase
{
    public function __invoke(array $data): User
    {
        $user = DB::transaction(function () use ($data) {
            $user = new User;
            $user->fill($data['user'])->save();
            $user->customer()->create($data['customer']);

            $user->assignRole(Role::findByName('user', 'user'));

            return $user;
        });

        $user->sendEmailVerificationNotification();

        return $user;
    }
}
