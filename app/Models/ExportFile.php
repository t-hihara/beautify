<?php

namespace App\Models;

use App\Enum\ExportFileStatusTypeEnum;
use Illuminate\Database\Eloquent\Model;

class ExportFile extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'format',
        'filename',
        'file_path',
        'size',
        'status',
        'filters',
        'error_message',
    ];

    protected $casts = [
        'status' => ExportFileStatusTypeEnum::class,
    ];
}
