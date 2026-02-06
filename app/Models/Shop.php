<?php

namespace App\Models;

use App\Enum\ActiveFlagTypeEnum;
use App\Models\Traits\LogsActivity;
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
