<?php

namespace App\UseCases\Shop;

use App\Models\Shop;
use App\Models\ShopBusinessHour;
use App\Utilities\RecursiveCovert;
use Illuminate\Support\Facades\DB;

class UpdateShopUseCase
{
    public function __invoke(array $payload, Shop $shop): Shop
    {
        return DB::transaction(function () use ($payload, $shop) {
            $convert = RecursiveCovert::_convert($payload, 'snake');
            $shop->load(['businessHours']);

            unset($convert['updated_at']);
            $shop->fill($convert)->save();

            $rows = [];
            foreach ($convert['business_hours'] as $businessHour) {
                $rows[] = [
                    'shop_id'     => $shop->id,
                    'day_of_week' => $businessHour['day_of_week'],
                    'open_time'   => $businessHour['open_time'],
                    'close_time'  => $businessHour['close_time'],
                ];
            }
            ShopBusinessHour::upsert(
                $rows,
                ['shop_id', 'day_of_week'],
                ['open_time', 'close_time'],
            );

            return $shop;
        });
    }
}
