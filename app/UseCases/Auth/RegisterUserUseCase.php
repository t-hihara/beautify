<?php

namespace App\UseCases\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RegisterUserUseCase
{
    public function __invoke(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = new User;
            $user->fill($data)->save();

            $user->assignRole(Role::findByName('user', 'user'));

            return $user;
        });
    }
}
