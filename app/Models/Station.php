<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Station extends Model
{
    protected $fillable = [
        'prefecture_id',
        'area_id',
        'name',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
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

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function prefecture(): BelongsTo
    {
        return $this->belongsTo(Prefecture::class);
    }

    public function shops(): HasMany
    {
        return $this->hasMany(Shop::class);
    }
}
