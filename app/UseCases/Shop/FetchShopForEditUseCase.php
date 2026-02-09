<?php

namespace App\UseCases\Shop;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\DayOfWeekTypeEnum;
use App\Models\Prefecture;
use App\Models\Shop;

class FetchShopForEditUseCase
{
    private const DAY_ORDER = [
        DayOfWeekTypeEnum::MONDAY,
        DayOfWeekTypeEnum::TUESDAY,
        DayOfWeekTypeEnum::WEDNESDAY,
        DayOfWeekTypeEnum::THURSDAY,
        DayOfWeekTypeEnum::FRIDAY,
        DayOfWeekTypeEnum::SATURDAY,
        DayOfWeekTypeEnum::SUNDAY,
    ];

    public function __invoke(Shop $shop): array
    {
        $shop->load(['businessHours']);
        $byDay = $shop->businessHours->keyBy(fn($h) => $h->day_of_week->value);

        $businessHours = [];
        foreach (self::DAY_ORDER as $day) {
            $h = $byDay->get($day->value);
            $businessHours[] = [
                'id'        => $h?->id,
                'dayOfWeek' => $day->value,
                'label'     => $day->description(),
                'openTime'  => $h?->open_time,
                'closeTime' => $h?->close_time,
            ];
        }

        return [
            'shop' => [
                'id'            => $shop->id,
                'name'          => $shop->name,
                'email'         => $shop->email,
                'phone'         => $shop->phone,
                'prefectureId'  => $shop->prefecture_id,
                'zipcode'       => $shop->zipcode,
                'address'       => $shop->address,
                'building'      => $shop->building,
                'description'   => $shop->description,
                'activeFlag'    => $shop->active_flag->value,
                'businessHours' => $businessHours,
            ],
            'activeFlags' => ActiveFlagTypeEnum::options(),
            'prefectures' => Prefecture::get(['id', 'name']),
        ];
    }
}
