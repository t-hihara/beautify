<?php

namespace App\Models;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\ShopStaffPositionTypeEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ShopStaff extends Model
{
    protected $fillable = [
        'shop_id',
        'user_id',
        'name',
        'email',
        'position',
        'description',
        'experience_years',
        'active_flag',
    ];

    protected $casts = [
        'position'    => ShopStaffPositionTypeEnum::class,
        'active_flag' => ActiveFlagTypeEnum::class,
    ];

    /* ================================================================================
                                        アクセサ
    ================================================================================ */

    /* ================================================================================
                                        スコープ
    ================================================================================ */

    public function scopeByName(Builder $query, ?string $name): Builder
    {
        return $query->when($name, fn($q) => $q->where('name', 'like', "%$name%"));
    }

    public function scopeByActiveFlag(Builder $query, ?string $flag): Builder
    {
        return $query->when($flag, fn($q) => $q->where('active_flag', $flag));
    }

    public function scopeByShopIds(Builder $query, ?array $shopIds): Builder
    {
        return $query->when($shopIds, fn($q) => $q->whereIn('shop_id', $shopIds));
    }

    public function scopeByPositions(Builder $query, ?array $positions): Builder
    {
        return $query->when($positions, fn($q) => $q->whereIn('position', $positions));
    }

    /* ================================================================================
                                        リレーション
    ================================================================================ */

    public function image(): MorphOne
    {
        return $this->morphOne(UploadedImage::class, 'imageable');
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
