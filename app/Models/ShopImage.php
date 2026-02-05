<?php

namespace App\Models;

use App\Models\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopImage extends Model
{
    use LogsActivity;

    protected $fillable = [
        'shop_id',
        'file_path',
        'filename',
        'mime_type',
        'file_size',
    ];

    public function getDescriptionValue(): string
    {
        return "店舗画像ID「{$this->id}」";
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
