<?php

namespace App\Enum;

use App\Enum\Traits\HasOptions;

enum DayOfWeekTypeEnum: string
{
    use HasOptions;

    case SUNDAY    = 'sunday';
    case MONDAY    = 'monday';
    case TUESDAY   = 'tuesday';
    case WEDNESDAY = 'wednesday';
    case THURSDAY  = 'thursday';
    case FRIDAY    = 'friday';
    case SATURDAY  = 'saturday';

    public function description(): string
    {
        return match ($this) {
            self::SUNDAY    => '日',
            self::MONDAY    => '月',
            self::TUESDAY   => '火',
            self::WEDNESDAY => '水',
            self::THURSDAY  => '木',
            self::FRIDAY    => '金',
            self::SATURDAY  => '土',
        };
    }
}
