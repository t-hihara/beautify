<?php

namespace App\UseCases\Shop;

use App\Enum\ActiveFlagTypeEnum;
use App\Models\Prefecture;
use App\Models\Shop;

class FetchShopForEditUseCase
{
    public function __invoke(Shop $shop): array
    {
        $shop->load(['prefecture', 'businessHours']);

        return [
            'shop' => [
                'id'            => $shop->id,
                'name'          => $shop->name,
                'email'         => $shop->email,
                'phone'         => $shop->phone,
                'prefecture'    => $shop->prefecture->name,
                'zipcode'       => $shop->zipcode,
                'address'       => $shop->address,
                'building'      => $shop->building,
                'description'   => $shop->description,
                'activeFlag'    => $shop->active_flag->description(),
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
