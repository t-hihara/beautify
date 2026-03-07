<?php

namespace App\UseCases\Operator\Plan;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\PlanConditionTypeEnum;
use App\Models\Menu;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class PreparePlanCreateFormUseCase
{
    public function __invoke(?int $shopId = null): array
    {
        return [
            'activeFlags'    => ActiveFlagTypeEnum::options(),
            'conditionTypes' => PlanConditionTypeEnum::options(),
            'shops'          => $this->shopsForForm($shopId),
            'menus'          => $shopId ? $this->menusForForm($shopId) : [],
        ];
    }

    private function shopsForForm(?int $shopId): Collection
    {
        return Shop::query()
            ->when($shopId, fn(Builder $q, $id) => $q->where('id', $id))
            ->get(['id', 'name']);
    }

    private function menusForForm(int $shopId): array
    {
        return Menu::query()
            ->where('shop_id', $shopId)
            ->orderBy('sort_order')
            ->get()
            ->groupBy(fn($menu) => $menu->type->description())
            ->map(fn($menus, $label) => [
                'label' => $label,
                'items' => $menus->map(fn($menu) => [
                    'id'       => $menu->id,
                    'shopId'   => $menu->shop_id,
                    'name'     => $menu->name,
                    'type'     => $menu->type->value,
                    'price'    => $menu->price,
                    'duration' => $menu->duration,
                ])->values()->toArray(),
            ])->values()->toArray();
    }
}
