<?php

namespace App\UseCases\Manager\Plan;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\PlanConditionTypeEnum;
use App\Models\Menu;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Builder;

class PreparePlanCreateFormUseCase
{
    public function __invoke(?int $shopId = null): array
    {
        return [
            'activeFlags'    => ActiveFlagTypeEnum::options(),
            'conditionTypes' => PlanConditionTypeEnum::options(),
            'shops'          => $this->shopsForForm($shopId),
            'menus'          => $this->menusForForm($shopId)
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

    private function shopsForForm(?int $shopId)
    {
        return Shop::query()
            ->when($shopId, fn(Builder $q, $id) => $q->where('id', $id))
            ->get(['id', 'name']);
    }

    private function menusForForm(?int $shopId)
    {
        return Menu::query()
            ->when($shopId, fn(Builder $q, $sid) => $q->where('shop_id', $sid))
            ->orderBy('sort_order')
            ->get();
    }
}
