<?php

namespace App\UseCases\Shop;

use App\Models\Shop;
use App\Utilities\RecursiveCovert;
use Illuminate\Support\Facades\DB;

class UpdateShopUseCase
{
    public function __invoke(array $payload, Shop $shop): Shop
    {
        return DB::transaction(function () use ($payload, $shop) {
            $convert = RecursiveCovert::_convert($payload);
            $shop->load(['businessHours']);

            $shop->fill(unset($convert['business_hours']))->save();
            foreach ($convert['business_hours'] as $businessHour) {
                $shop->businessHours->updateOrCreate(
                    [
                        'shop_id'     => $shop->id,
                        'day_of_week' => $businessHour['day_of_week'],
                    ],
                    [
                        'open_time'  => $businessHour['open_time'],
                        'close_time' => $businessHour['close_time'],
                    ],
                );
            }

            return $shop;
        });
    }
}
