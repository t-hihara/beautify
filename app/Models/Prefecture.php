<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prefecture extends Model
{
    protected $fillable = [
        'name'
    ];

    /* ================================================================================
                                        アクセサ
    ================================================================================ */

    /* ================================================================================
                                        リレーション
    ================================================================================ */

    public function areas(): HasMany
    {
        return $this->hasMany(Area::class);
    }

    public function shops(): HasMany
    {
        return $this->hasMany(Shop::class);
    }

    public function stations(): HasMany
    {
        return $this->hasMany(Station::class);
    }
}
