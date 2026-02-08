<?php

namespace App\UseCases\Shop;

use App\Enum\ActiveFlagTypeEnum;
use App\Models\Prefecture;
use App\Models\Shop;

class FetchShopForEditUseCase
{
    public function __invoke(Shop $shop): array
    {
        $shop->load(['businessHours']);

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
                'businessHours' => $shop->businessHours->map(fn($businessHour) => [
                    'id'        => $businessHour->id,
                    'dayOfWeek' => $businessHour->day_of_week->description(),
                    'openTime'  => $businessHour->open_time,
                    'closeTime' => $businessHour->close_time,
                ]),
            ],
            'activeFlags' => ActiveFlagTypeEnum::options(),
            'prefectures' => Prefecture::get(['id', 'name']),
        ];
    }
}
