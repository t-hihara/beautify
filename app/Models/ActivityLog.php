<?php

namespace App\Models;

use App\Models\Traits\LogsActivity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;

class ActivityLog extends Activity
{
    protected $cases = [
        'created_at' => 'datetime',
    ];

    public function getCreatedAtAttribute($value): string
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function scopeByName(Builder $query, ?string $name): Builder
    {
        if ($name) {
            return $query->whereHas('causer', function ($q) use ($name) {
                $q->where('last_name', 'like', "%$name%")
                    ->orWhere('first_name', 'like', "%$name%");
            });
        }

        return $query;
    }

    public function scopeByDuration(Builder $query, ?string $from, ?string $to): Builder
    {
        if ($from && $to) {
            return $query->whereBetween('created_at', [$from, $to]);
        }
        if ($from) {
            return $query->where('created_at', '>=', $from);
        }
        if ($to) {
            return $query->where('created_at', '<=', $to);
        }

        return $query;
    }
}
