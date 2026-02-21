<?php

namespace App\Models;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\MenuTypeEnum;
use App\Models\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
