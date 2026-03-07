<?php

namespace App\UseCases\Operator\Menu;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\MenuTypeEnum;
use App\Models\Menu;
use App\Models\Shop;

class PrepareMenuEditFormUseCase
{
    public function __invoke(Menu $menu): array
    {
        return [
            'menu' => [
                'id'          => $menu->id,
                'shopId'      => $menu->shop_id,
                'name'        => $menu->name,
                'type'        => $menu->type->value,
                'price'       => $menu->price,
                'duration'    => $menu->duration,
                'description' => $menu->description,
                'activeFlag' => $menu->active_flag->value,
                'sortOrder'   => $menu->sort_order,
            ],
            'activeFlags' => ActiveFlagTypeEnum::options(),
            'menuTypes'   => MenuTypeEnum::options(),
            'shops'       => [],
        ];
    }
}
