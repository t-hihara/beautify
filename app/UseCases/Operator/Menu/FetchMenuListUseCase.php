<?php

namespace App\UseCases\Operator\Menu;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\MenuTypeEnum;
use App\Models\Menu;
use App\Models\Shop;
use App\Utilities\RecursiveCovert;
use Illuminate\Database\Eloquent\Builder;

class FetchMenuListUseCase
{
    public function __invoke(array $filters, ?int $shopId = null): array
    {
        $convert = RecursiveCovert::_convert($filters, 'snake');
        if ($shopId) {
            $convert['shop_ids'] = [$shopId];
        }

        $menus = $this->queryWithFilters($convert)
            ->paginate($convert['per_page'] ?? 10)
            ->withQueryString()
            ->through(fn($menu) => [
                'id'          => $menu->id,
                'name'        => $menu->name,
                'type'        => $menu->type->description(),
                'price'       => $menu->price,
                'duration'    => $menu->duration,
                'description' => $menu->description,
                'activeFlag'  => $menu->active_flag->description(),
                'sortOrder'   => $menu->sortOrder,
                'shop'        => $menu->shop->only(['id', 'name']),
            ]);

        return [
            'filters'     => array_merge($filters, [
                'perPage' => (int) ($filters['perPage'] ?? 10),
            ]),
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

    private function queryWithFilters(array $convert): Builder
    {
        return Menu::with(['shop'])
            ->when($convert['name'] ?? null, fn(Builder $query, $name) => $query->where('name', 'like', "$name"))
            ->when($convert['shop_ids'] ?? null, fn(Builder $query, $shopIds) => $query->whereIn('shop_id', $shopIds))
            ->when($convert['types'] ?? null, fn(Builder $query, $types) => $query->whereIn('types', $types))
            ->when($convert['active_flat'] ?? null, fn(Builder $query, $flag) => $query->where('active_flag', $flag));
    }
}
