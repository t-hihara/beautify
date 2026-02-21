<?php

namespace App\UseCases\Menu;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\MenuTypeEnum;
use App\Models\Menu;
use App\Models\Shop;
use App\Utilities\RecursiveCovert;

class FetchMenuListUseCase
{
    public function __invoke(array $filters, ?int $shopId = null): array
    {
        $convert = RecursiveCovert::_convert($filters, 'snake');
        $shopId ? $convert['shop_ids'] = $shopId : null;

        $menus = Menu::with(['shop'])
            ->byName($convert['name'] ?? null)
            ->byShopIds($convert['shop_ids'] ?? null)
            ->byTypes($convert['types'] ?? null)
            ->byActiveFlag($convert['active_flag'] ?? null)
            ->paginate(20)
            ->through(fn($menu) => [
                'id'          => $menu->id,
                'name'        => $menu->name,
                'type'        => $menu->type->description(),
                'price'       => $menu->price,
                'duration'    => $menu->duration,
                'description' => $menu->description,
                'activeFlag'  => $menu->active_flag->description(),
                'sortOrder'   => $menu->sortOrder,
                'shop'        => [
                    'id'   => $menu->shop->id,
                    'name' => $menu->shop->name,
                ],
            ]);

        return [
            'filters'     => $filters,
            'menuTypes'   => MenuTypeEnum::options(),
            'activeFlags' => ActiveFlagTypeEnum::options(),
            'shops'       => Shop::get(['id', 'name']),
            'menus'       => $menus->items(),
            'links'       => $menus->linkCollection(),
            'pagination'  => [
                'currentPage' => $menus->currentPage(),
                'lastPage'    => $menus->lastPage(),
                'prev'        => $menus->previousPageUrl(),
                'next'        => $menus->nextPageUrl(),
                'total'       => $menus->total(),
            ],
        ];
    }
}
