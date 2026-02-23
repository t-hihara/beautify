<?php

namespace App\Models;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\ShopStaffPositionTypeEnum;
use App\Models\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ShopStaff extends Model
{
    use LogsActivity;

    protected $appends = [
        'name'
    ];

    protected $fillable = [
        'shop_id',
        'user_id',
        'last_name',
        'first_name',
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

    public function getDescriptionValue(): string
    {
        return "店舗スタッフID「{$this->id}」";
    }

    /* ================================================================================
                                        アクセサ
    ================================================================================ */

    public function getNameAttribute(): string
    {
        return $this->last_name . ' ' . $this->first_name;
    }

    /* ================================================================================
                                        スコープ
    ================================================================================ */

    public function scopeByName(Builder $query, ?string $name): Builder
    {
        return $query->when($name, fn(Builder $q) => $q->where('name', 'like', "%$name%"));
    }

    public function scopeByActiveFlag(Builder $query, ?string $flag): Builder
    {
        return $query->when($flag, fn(Builder $q) => $q->where('active_flag', $flag));
    }

    public function scopeByShopIds(Builder $query, ?array $shopIds): Builder
    {
        return $query->when($shopIds, fn(Builder $q) => $q->whereIn('shop_id', $shopIds));
    }

    public function scopeByPositions(Builder $query, ?array $positions): Builder
    {
        return $query->when($positions, fn(Builder $q) => $q->whereIn('position', $positions));
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
