<?php

namespace App\Services\Shop;

use App\Enum\DayOfWeekTypeEnum;
use Illuminate\Database\Eloquent\Collection;

class ShopBusinessHourFormatter
{
    private const ORDER = [
        DayOfWeekTypeEnum::MONDAY->value    => 0,
        DayOfWeekTypeEnum::TUESDAY->value   => 1,
        DayOfWeekTypeEnum::WEDNESDAY->value => 2,
        DayOfWeekTypeEnum::THURSDAY->value  => 3,
        DayOfWeekTypeEnum::FRIDAY->value    => 4,
        DayOfWeekTypeEnum::SATURDAY->value  => 5,
        DayOfWeekTypeEnum::SUNDAY->value    => 6,
    ];

    private const LABELS = ['月', '火', '水', '木', '金', '土', '日'];

    public function format(Collection $businessHours): string
    {
        $open = $businessHours->filter(fn($h) => $h->open_time !== null && $h->close_time !== null);
        if ($open->isEmpty()) return '休業';

        $groups = $open->groupBy(fn($h) => $h->open_time . "-" . $h->close_time);
        $minDisplayOrder = $groups->map(
            fn($group) => $group->map(
                fn($h) => self::ORDER[$h->day_of_week->value] ?? 7
            )->min()
        );
        $sortedKeys = $groups
            ->keys()
            ->sortBy(fn($k) => $minDisplayOrder[$k])
            ->values();

        $parts = [];
        foreach ($sortedKeys as $key) {
            $g        = $groups[$key];
            $first    = $g->first();
            $orders   = $g->map(fn($h) => self::ORDER[$h->day_of_week->value] ?? 7)->unique()->sort()->values();
            $dayLabel = $orders->map(fn($i) => self::LABELS[$i])->implode('・');
            $parts[]  = $dayLabel . ' ' . $first->open_time . "〜" . $first->close_time;
        }

        return implode(' / ', $parts);
    }
}
