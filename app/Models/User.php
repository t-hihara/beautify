<?php

namespace App\Models;

use App\Enum\ActiveFlagTypeEnum;
use App\Models\Traits\LogsActivity;
use App\Notifications\Auth\ResetPasswordNotification;
use App\Notifications\Auth\VerifyEmailNotification;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use LaravelAndVueJS\Traits\LaravelPermissionToVueJS;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use HasFactory, Notifiable, MustVerifyEmail, HasRoles, LaravelPermissionToVueJS, LogsActivity;

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

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function getDescriptionValue(): string
    {
        return "ユーザーID「{$this->id}」";
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

    public function scopeByEmail(Builder $query, ?string $email): Builder
    {
        if ($email) {
            return $query->where('email', $email);
        }

        return $query;
    }

    /* ================================================================================
                                        リレーション
    ================================================================================ */

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class);
    }

    public function shopStaff(): HasOne
    {
        return $this->hasOne(ShopStaff::class);
    }
}
