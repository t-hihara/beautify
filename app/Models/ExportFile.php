<?php

namespace App\Models;

use App\Enum\ExportFileStatusTypeEnum;
use App\Models\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
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
    ];

    protected $casts = [
        'status'  => ExportFileStatusTypeEnum::class,
        'filters' => 'array',
    ];

    public function getDescriptionValue(): string
    {
        return "ファイルID「{$this->id}」";
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

    public function scopeByUserId(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeBySubject(Builder $query, ?string $subject): Builder
    {
        return $query->when('subject', fn($q) => $q->where('subject', 'like', "%$subject%"));
    }

    public function scopeByDuration(Builder $query, ?string $from): Builder
    {
        return $query
            ->when($from && $to, fn(Builder $q) => $q->whereBetween('created_at', [$from, $to]))
            ->when($from && !$to, fn(Builder $q) => $q->where('created_at', '>=', $from))
            ->when(!$from && $to, fn(Builder $q) => $q->where('created_at', '<=', $to));
    }
}
