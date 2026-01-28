<?php

namespace App\UseCases\Auth;

use App\Enum\ActiveFlagTypeEnum;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Spatie\Permission\Models\Role;

class UserGoogleLoginUseCase
{
    public function execute(SocialiteUser $googleUser): array
    {
        return DB::transaction(function () use ($googleUser) {
            $user = $this->findOrCreateUser($googleUser);

            if (!$user) {
                return [
                    'success' => false,
                    'error'   => 'このアカウントではログインできません。',
                ];
            }

            // TODO: ユーザー情報用テーブルを作成したらここにそのテーブルへのデータ保存ロジックを書く

            auth()->guard('user')->login($user);

            return [
                'success' => true,
                'user'    => $user,
            ];
        });
    }

    private function findOrCreateUser(SocialiteUser $googleUser): ?User
    {
        $user = User::where('google_id', $googleUser->id)
            ->orWhere('email', $googleUser->email)
            ->first();

        if (!$user) {
            return $this->createNewUser($googleUser);
        }

        if (!$user->hasRole('user', 'user')) {
            return null;
        }

        return $user;
    }

    private function createNewUser(SocialiteUser $googleUser): User
    {
        $user = User::create([
            'last_name'         => $googleUser->user['family_name'] ?? '',
            'first_name'        => $googleUser->user['given_name'] ?? '',
            'email'             => $googleUser->email,
            'email_verified_at' => now(),
            'google_id'         => $googleUser->id,
            'active_flag'       => ActiveFlagTypeEnum::ACTIVE,
        ]);

        $user->assignRole(Role::findByName('user', 'user'));

        return $user;
    }
}
