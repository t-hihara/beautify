<?php

namespace App\UseCases\Manager\Plan;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\PlanConditionTypeEnum;
use App\Models\Menu;
use App\Models\Shop;

class PreparePlanCreateFormUseCase
{
    public function __invoke(?int $shopId = null): array
    {
        return [
            'activeFlags'    => ActiveFlagTypeEnum::options(),
            'conditionTypes' => PlanConditionTypeEnum::options(),
            'shops'          => Shop::byId($shopId)->get(['id', 'name']),
            'menus'          => Menu::byShopId($shopId)
                ->orderBy('sort_order')
                ->get()
                ->groupBy(fn($menu) => $menu->type->description())
                ->map(fn($menus, $label) => [
                    'label' => $label,
                    'items' =>  $menus->map(fn($menu) => [
                        'id'       => $menu->id,
                        'shopId'   => $menu->shop_id,
                        'name'     => $menu->name,
                        'type'     => $menu->type->value,
                        'price'    => $menu->price,
                        'duration' => $menu->duration,
                    ])->values()->toArray(),
                ])->values()->toArray(),
        ];
    }
}
