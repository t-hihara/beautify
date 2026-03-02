<?php

namespace App\Models;

use App\Enum\ExportFileStatusTypeEnum;
use App\Models\Traits\LogsActivity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ExportFile extends Model
{
    use LogsActivity;

    protected $fillable = [
        'user_id',
        'subject',
        'filename',
        'file_type',
        'file_path',
        'file_size',
        'status',
        'filters',
        'error_message',
        'downloaded_at',
    ];

    protected $casts = [
        'status'        => ExportFileStatusTypeEnum::class,
        'filters'       => 'array',
        'downloaded_at' => 'datetime',
        'created_at'    => 'datetime',
    ];

    public function getDescriptionValue(): string
    {
        return "ファイルID「{$this->id}」";
    }

    /* ================================================================================
                                        アクセサ
    ================================================================================ */

    public function getCreatedAtAttribute($value): string
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function getDownloadedAtAttribute($value): ?string
    {
        return $value ? Carbon::parse($value)->format('Y-m-d H:i:s') : null;
    }

    /* ================================================================================
                                        リレーション
    ================================================================================ */
}
