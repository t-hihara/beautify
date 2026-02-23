<?php

namespace App\Models;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\PlanConditionTypeEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Plan extends Model
{
    protected $fillable = [
        'shop_id',
        'name',
        'description',
        'total_duration',
        'regular_price',
        'selling_price',
        'condition_type',
        'active_flag',
        'sort_order',
        'valid_from',
        'valid_to',
    ];

    protected $casts = [
        'regular_price'  => 'integer',
        'selling_price'  => 'integer',
        'condition_type' => PlanConditionTypeEnum::class,
        'active_flag'    => ActiveFlagTypeEnum::class,
        'sort_order'     => 'integer',
        'valid_from'     => 'date',
        'valid_to'       => 'date',
    ];

    /* ================================================================================
                                        アクセサ
    ================================================================================ */

    public function getValidFromAttribute($value): ?string
    {
        return $value ? Carbon::parse($value)->format('Y-m-d') : null;
    }

    public function getValidToAttribute($value): ?string
    {
        return $value ? Carbon::parse($value)->format('Y-m-d') : null;
    }

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

    public function scopeByValidDuration(Builder $query, ?string $from, ?string $to): Builder
    {
        return $query
            ->when($from && $to, fn(Builder $q) => $q
                ->where('valid_from', '>=', $from)
                ->where('valid_to', '<=', $to))
            ->when($from && !$to, fn(Builder $q) => $q
                ->where('valid_from', '>=', $from))
            ->when(!$from && $to, fn(Builder $q) => $q
                ->where('valid_to', '<=', $to));
    }

    /* ================================================================================
                                        リレーション
    ================================================================================ */

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class);
    }
}
