<?php

namespace App\UseCases\Manager\Shop;

use App\Enum\ActiveFlagTypeEnum;
use App\Models\Area;
use App\Models\Prefecture;
use App\Models\Shop;
use App\Models\Station;
use App\Services\Shop\ShopBusinessHourFormatter;
use App\Utilities\RecursiveCovert;
use Illuminate\Database\Eloquent\Builder;

class FetchShopListUseCase
{
    public function __invoke(array $filters): array
    {
        $convert = RecursiveCovert::_convert($filters, 'snake');

        $shops = $this->queryWithFilters($convert)
            ->orderBy('id')
            ->paginate($convert['per_page'] ?? 10)
            ->withQueryString()
            ->through(fn($shop) => [
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
                'areaName'    => $shop->area?->name,
                'stationName' => $shop->station?->name,
            ]);

        return [
            'filters'     => array_merge($filters, [
                'perPage' => (int) ($filters['perPage'] ?? 10),
            ]),
            'activeFlags' => ActiveFlagTypeEnum::options(),
            'prefectures' => Prefecture::get(['id', 'name']),
            'shops'       => $shops->items(),
            'links'       => $shops->linkCollection(),
            'pagination'  => [
                'currentPage' => $shops->currentPage(),
                'lastPage'    => $shops->lastPage(),
                'prev'        => $shops->previousPageUrl(),
                'next'        => $shops->nextPageUrl(),
                'total'       => $shops->total(),
            ],
        ];
    }

    private function queryWithFilters(array $convert): Builder
    {
        return Shop::with(['area', 'businessHours', 'prefecture', 'station'])
            ->when($convert['name'] ?? null, fn(Builder $query, $name) => $query->where('name', 'like', "%$name%"))
            ->when($convert['email'] ?? null, fn(Builder $query, $email) => $query->where('email', 'like', "$email"))
            ->when($convert['phone'] ?? null, fn(Builder $query, $phone) => $query->where('phone', $phone))
            ->when($convert['prefecture_ids'] ?? null, fn(Builder $query, $prefectures) => $query->whereIn('prefecture_id', $prefectures))
            ->when($convert['active_flag'] ?? null, fn(Builder $query, $flag) => $query->where('active_flag', $flag));
    }
}
