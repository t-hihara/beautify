<?php

namespace App\Models;

use App\Enum\ActiveFlagTypeEnum;
use App\Notifications\Auth\VerifyEmailNotification;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use LaravelAndVueJS\Traits\LaravelPermissionToVueJS;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use HasFactory, Notifiable, MustVerifyEmail, HasRoles, LaravelPermissionToVueJS;

    protected $appends = ['name', 'name_kana'];

    protected $fillable = [
        'last_name',
        'first_name',
        'last_name_kana',
        'first_name_kana',
        'email',
        'password',
        'google_id',
        'active_flag',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'active_flag'       => ActiveFlagTypeEnum::class,
    ];

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification);
    }

    /* ================================================================================
                                        アクセサ
    ================================================================================ */

    public function getNameAttribute(): string
    {
        return $this->last_name . ' ' . $this->first_name;
    }

    public function getNameKanaAttribute(): ?string
    {
        return $this->last_name_kana || $this->first_name_kana ? $this->last_name_kana . ' ' . $this->first_name_kana : null;
    }

    /* ================================================================================
                                        スコープ
    ================================================================================ */

    /* ================================================================================
                                        リレーション
    ================================================================================ */

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class);
    }
}
