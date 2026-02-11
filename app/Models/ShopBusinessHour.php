<?php

namespace App\Models;

use App\Enum\DayOfWeekTypeEnum;
use App\Models\Traits\LogsActivity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopBusinessHour extends Model
{
    use LogsActivity;

    protected $fillable = [
        'shop_id',
        'day_of_week',
        'open_time',
        'close_time',
    ];

    protected $casts = [
        'day_of_week' => DayOfWeekTypeEnum::class,
        'open_time'   => 'string',
        'close_time'  => 'string',
    ];

    public function getDescriptionValue(): string
    {
        return "店舗ID「{$this->shop_id}」の営業時間ID「{$this->id}」";
    }

    /* ================================================================================
                                        アクセサ
    ================================================================================ */

    public function getOpenTimeAttribute($value): ?string
    {
        return $value ? Carbon::parse($value)->format('H:i') : null;
    }

    public function getCloseTimeAttribute($value): ?string
    {
        return $value ? Carbon::parse($value)->format('H:i') : null;
    }

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
