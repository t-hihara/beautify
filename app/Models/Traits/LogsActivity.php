<?php

namespace App\Models\Traits;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity as BaseLogsActivity;

trait LogsActivity
{
    use BaseLogsActivity;

    abstract public function getDescriptionValue(): string;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $event) => $this->setEventDescription($event))
            ->useLogName('system');
    }

    public function setEventDescription(string $event): string
    {
        $value = $this->getDescriptionValue();
        return match ($event) {
            'created' => "{$value}を作成しました。",
            'updated' => "{$value}を更新しました。",
            'deleted' => "{$value}を削除しました。",
            default   => "{$value}に{$event}が発生しました。",
        };
    }
}
