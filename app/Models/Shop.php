<?php

namespace App\Models;

use App\Enum\ActiveFlagTypeEnum;
use App\Models\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Shop extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'prefecture_id',
        'zipcode',
        'address',
        'building',
        'description',
        'active_flag',
    ];

    protected $casts = [
        'active_flag' => ActiveFlagTypeEnum::class,
    ];

    public function getDescriptionValue(): string
    {
        return "店舗ID「{$this->id}」";
    }

    /* ================================================================================
                                        アクセサ
    ================================================================================ */

    public function scopeByName(Builder $query, ?string $name): Builder
    {
        return $query->when($name, fn(Builder $q) => $q->where('name', 'like', "%$name%"));
    }

    public function scopeByEmail(Builder $query, ?string $email): Builder
    {
        return $query->when($email, fn(Builder $q) => $q->where('email', 'like', "%$email%"));
    }

    public function scopeByPhone(Builder $query, ?string $phone): Builder
    {
        return $query->when($phone, fn(Builder $q) => $q->where('phone', $phone));
    }

    public function scopeByPrefectures(Builder $query, ?array $prefectures): Builder
    {
        return $query->when($prefectures, fn(Builder $q) => $q->whereIn('prefecture_id', $prefectures));
    }

    public function scopeByActiveFlag(Builder $query, ?string $activeFlag): Builder
    {
        return $query->when($activeFlag, fn(Builder $q) => $q->where('active_flag', $activeFlag));
    }

    /* ================================================================================
                                        スコープ
    ================================================================================ */

    /* ================================================================================
                                        リレーション
    ================================================================================ */

    public function businessHours(): HasMany
    {
        return $this->hasMany(ShopBusinessHour::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ShopImage::class);
    }

    public function prefecture(): BelongsTo
    {
        return $this->belongsTo(Prefecture::class);
    }

    public function shopStaff(): HasOne
    {
        return $this->hasOne(ShopStaff::class);
    }
}
