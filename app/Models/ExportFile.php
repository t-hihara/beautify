<?php

namespace App\Models;

use App\Enum\ExportFileStatusTypeEnum;
use App\Models\Traits\LogsActivity;
use Carbon\Carbon;
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
        return $query->when($subject, fn($q) => $q->where('subject', 'like', "%$subject%"));
    }

    public function scopeByStatus(Builder $query, ?ExportFileStatusTypeEnum $status): Builder
    {
        return $query->when($status, fn($q) => $q->where('status', $status));
    }

    public function scopeByDownloaded(Builder $query, bool $downloaded = false): Builder
    {
        return $downloaded ? $query->whereNotNull('downloaded_at') : $query->whereNull('downloaded_at');
    }

    public function scopeByDuration(Builder $query, ?string $from, ?string $to): Builder
    {
        return $query
            ->when($from && $to, fn(Builder $q) => $q->whereBetween('created_at', [$from, $to]))
            ->when($from && !$to, fn(Builder $q) => $q->where('created_at', '>=', $from))
            ->when(!$from && $to, fn(Builder $q) => $q->where('created_at', '<=', $to));
    }
}
