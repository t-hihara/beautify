<?php

namespace App\Models;

use App\Enum\ActiveFlagTypeEnum;
use App\Models\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function byName(Builder $query, ?string $name): Builder
    {
        return $query->when($name, fn(Builder $q) => $q->where('name', 'like', "%$name%"));
    }

    public function byEmail(Builder $query, ?string $email): Builder
    {
        return $query->when($email, fn(Builder $q) => $q->where('email', 'like', "%$email%"));
    }

    public function byPhone(Builder $query, ?string $phone): Builder
    {
        return $query->when($phone, fn(Builder $q) => $q->where('phone', $phone));
    }

    public function byPrefectures(Builder $query, ?array $prefectures): Builder
    {
        return $query->when($prefectures, fn(Builder $q) => $q->whereIn('prefecture_id', $prefectures));
    }

    public function byActiveFlag(Builder $query, ?string $activeFlag): Builder
    {
        return $query->when($activeFlag, fn(Builder $q) => $q->where('active_flag', $activeFlag));
    }

    /* ================================================================================
                                        スコープ
    ================================================================================ */

    /* ================================================================================
                                        リレーション
    ================================================================================ */

    public function shopImages(): HasMany
    {
        return $this->hasMany(ShopImage::class);
    }

    public function prefecture(): BelongsTo
    {
        return $this->belongsTo(Prefecture::class);
    }
}
