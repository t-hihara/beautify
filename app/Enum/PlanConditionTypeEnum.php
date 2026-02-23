<?php

namespace App\Enum;

use App\Enum\Traits\HasOptions;

enum PlanConditionTypeEnum: string
{
    use HasOptions;

    case FIRST_VISIT = 'first_visit';
    case WEEKDAY     = 'weekday';
    case WEEKEND     = 'weekend';
    case PERIOD      = 'period';

    public function description(): string
    {
        return match ($this) {
            self::FIRST_VISIT => '初回限定',
            self::WEEKDAY     => '平日限定',
            self::WEEKEND     => '週末限定',
            self::PERIOD      => '期間限定',
        };
    }
}
