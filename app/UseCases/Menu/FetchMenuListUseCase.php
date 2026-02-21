<?php

namespace App\UseCases\Menu;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\MenuTypeEnum;
use App\Models\Menu;
use App\Utilities\RecursiveCovert;

class FetchMenuListUseCase
{
    public function __invoke(array $filters): array
    {
        $convert = RecursiveCovert::_convert($filters, 'snake');

        $menus = Menu::byName($convert['name'] ?? null)
            ->byTypes($convert['menu_types'] ?? null)
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
            ]);

        return [
            'filters'     => $filters,
            'menuTypes'   => MenuTypeEnum::options(),
            'activeFlags' => ActiveFlagTypeEnum::options(),
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
