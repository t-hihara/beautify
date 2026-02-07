<?php

namespace App\UseCases\Shop;

use App\Enum\ActiveFlagTypeEnum;
use App\Models\Prefecture;
use App\Models\Shop;
use App\Utilities\RecursiveCovert;

class FetchShopListUseCase
{
    public function __invoke(array $filters): array
    {
        $convert = RecursiveCovert::_convert($filters, 'snake');

        $shops = Shop::with(['prefecture'])
            ->byName($convert['name'] ?? null)
            ->byEmail($convert['email'] ?? null)
            ->byPhone($convert['phone'] ?? null)
            ->byPrefectures($convert['prefecture_ids'] ?? null)
            ->byActiveFlag($convert['active_flag'] ?? null)
            ->orderBy('id')
            ->paginate($convert['per_page'] ?? 10)
            ->through(fn($shop) => [
                'id'          => $shop->id,
                'name'        => $shop->name,
                'email'       => $shop->email,
                'phone'       => $shop->phone,
                'prefecture'  => $shop->prefecture->name,
                'zipcode'     => $shop->zipcode,
                'address'     => $shop->address,
                'building'    => $shop->building,
                'description' => $shop->description,
                'activeFlag'  => $shop->active_flag->description(),
            ]);

        return [
            'filters'     => $filters,
            'activeFlags'  => ActiveFlagTypeEnum::options(),
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
}
