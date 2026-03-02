<?php

namespace App\Models;

use App\Models\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UploadedImage extends Model
{
    use LogsActivity;

    protected $fillable = [
        'disk',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'imageable_id',
        'imageable_type',
    ];

    public function getDescriptionValue(): string
    {
        return "画像ID「{$this->id}」";
    }

    /* ================================================================================
                                        リレーション
    ================================================================================ */

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
}
