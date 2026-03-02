<?php

namespace App\Models;

use App\Enum\ActiveFlagTypeEnum;
use App\Models\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

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
        'area_id',
        'station_id',
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
                                        リレーション
    ================================================================================ */

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function businessHours(): HasMany
    {
        return $this->hasMany(ShopBusinessHour::class);
    }

    public function mainImage(): MorphOne
    {
        return $this->morphOne(UploadedImage::class, 'imageable');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(UploadedImage::class, 'imageable');
    }

    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class);
    }

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class);
    }

    public function prefecture(): BelongsTo
    {
        return $this->belongsTo(Prefecture::class);
    }

    public function staffs(): HasMany
    {
        return $this->hasMany(ShopStaff::class);
    }

    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class);
    }
}
