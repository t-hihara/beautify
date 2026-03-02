<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopHoliday extends Model
{
    protected $fillable = [
        'shop_id',
        'close_date',
    ];

    protected $casts = [
        'close_date' => 'date',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
