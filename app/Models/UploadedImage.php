<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UploadedImage extends Model
{
    protected $fillable = [
        'disk',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'imageable_id',
        'imageable_type',
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

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
}
