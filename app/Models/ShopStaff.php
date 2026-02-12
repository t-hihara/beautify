<?php

namespace App\Models;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\ShopStaffPositionTypeEnum;
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
