<?php

namespace App\Models;

use App\Enum\GenderTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'name_kana',
        'email',
        'phone',
        'dob',
        'gender',
    ];

    protected $cases = [
        'dob'    => 'date',
        'gender' => GenderTypeEnum::class,
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
