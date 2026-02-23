<?php

namespace App\Models;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\MenuTypeEnum;
use App\Models\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Menu extends Model
{
    use LogsActivity;

    protected $fillable = [
        'shop_id',
        'name',
        'type',
        'price',
        'duration',
        'description',
        'active_flag',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'integer',
        'duration' => 'integer',
        'type' => MenuTypeEnum::class,
        'active_flag' => ActiveFlagTypeEnum::class,
    ];

    public function getDescriptionValue(): string
    {
        return "メニューID「{$this->id}」";
    }

    /* ================================================================================
                                        アクセサ
    ================================================================================ */

    public function scopeByName(Builder $query, ?string $name): Builder
    {
        return $query->when($name, fn(Builder $q) => $q->where('name', 'like', "%$name%"));
    }

    public function scopeByTypes(Builder $query, ?array $types): Builder
    {
        return $query->when($types, fn(Builder $q) => $q->whereIn("type", $types));
    }

    public function scopeByShopIds(Builder $query, ?array $shopIds): Builder
    {
        return $query->when($shopIds, fn(Builder $q) => $q->whereIn('shop_id', $shopIds));
    }

    public function scopeByActiveFlag(Builder $query, ?string $flag): Builder
    {
        return $query->when($flag, fn(Builder $q) => $q->where('active_flag', $flag));
    }

    /* ================================================================================
                                        スコープ
    ================================================================================ */

    /* ================================================================================
                                        リレーション
    ================================================================================ */

    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
