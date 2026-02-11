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
        return $query->when($name, function (Builder $q) use ($name) {
            $like = "%$name%";
            return $q->whereHas('causer', function (Builder $q2) use ($like) {
                $q2->where('last_name', 'like', $like)
                    ->orWhere('first_name', 'like', $like)
                    ->orWhereRaw('CONCAT(last_name, first_name) LIKE ?', [$like]);
            });
        });
    }

    public function scopeByEvent(Builder $query, ?string $event): Builder
    {
        return $query->when($event, fn($q) => $q->where('event', 'like', "%$event%"));
    }

    public function scopeByDescription(Builder $query, ?string $description): Builder
    {
        return $query->when($description, fn($q) => $q->where('description', 'like', "%$description%"));
    }

    public function scopeByDuration(Builder $query, ?string $from, ?string $to): Builder
    {
        return $query
            ->when($from && $to, fn(Builder $q) => $q->whereBetween('created_at', [$from, $to]))
            ->when($from && !$to, fn(Builder $q) => $q->where('created_at', '>=', $from))
            ->when(!$from && $to, fn(Builder $q) => $q->where('created_at', '<=', $to));
    }
}
