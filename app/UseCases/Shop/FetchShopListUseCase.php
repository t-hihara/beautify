<?php

namespace App\UseCases\Shop;

use App\Models\Shop;

class FetchShopListUseCase
{
    public function __invoke(): array
    {
        $shops = Shop::with(['prefecture'])
            ->paginate(20)
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
            'shops'      => $shops->items(),
            'links'      => $shops->linkCollection(),
            'pagination' => [
                'currentPage' => $shops->currentPage(),
                'lastPage'    => $shops->lastPage(),
                'prev'        => $shops->previousPageUrl(),
                'next'        => $shops->nextPageUrl(),
                'total'       => $shops->total(),
            ],
        ];
    }
}
