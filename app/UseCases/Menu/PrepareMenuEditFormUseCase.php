<?php

namespace App\UseCases\Menu;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\MenuTypeEnum;
use App\Models\Menu;

class PrepareMenuEditFormUseCase
{
    public function __invoke(Menu $menu): array
    {
        return [
            'menu' => [
                'id'          => $menu->id,
                'name'        => $menu->name,
                'type'        => $menu->type->value,
                'price'       => $menu->price,
                'duration'    => $menu->duration,
                'description' => $menu->description,
                'active_flag' => $menu->active_flag,
            ],
            'activeFlags' => ActiveFlagTypeEnum::options(),
            'menuTypes'   => MenuTypeEnum::options(),
        ];
    }
}
