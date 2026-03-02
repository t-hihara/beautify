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
