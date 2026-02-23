<?php

namespace App\Models;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\PlanConditionTypeEnum;
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

    /* ================================================================================
                                        スコープ
    ================================================================================ */

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
