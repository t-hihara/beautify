<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use LaravelAndVueJS\Traits\LaravelPermissionToVueJS;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, LaravelPermissionToVueJS;

    protected $fillable = [
        'last_name',
        'first_name',
        'last_name_kana',
        'first_name_kana',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /* ================================================================================
                                        アクセサ
    ================================================================================ */

    public function getFullName(): string
    {
        return $this->last_name . ' ' . $this->first_name;
    }

    public function getFullNameKana(): ?string
    {
        return $this->last_name_kana || $this->first_name_kana ? $this->last_name_kana . ' ' . $this->first_name_kana : null;
    }

    /* ================================================================================
                                        スコープ
    ================================================================================ */

    /* ================================================================================
                                        リレーション
    ================================================================================ */
}
