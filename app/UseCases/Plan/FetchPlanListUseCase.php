<?php

namespace App\UseCases\Plan;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\MenuTypeEnum;
use App\Models\Plan;
use App\Utilities\RecursiveCovert;

class FetchPlanListUseCase
{
    public function __invoke(array $filters, ?int $shopId = null): array
    {
        $convert = RecursiveCovert::_convert($filters, 'snake');

        $plans = Plan::with(['shop', 'menus'])
            ->byName($convert['name'] ?? null)
            ->byActiveFlag($convert['active_flag'] ?? null)
            ->byMenuTypes($convert['types'] ?? null)
            ->byValidDuration($convert['valid_from'] ?? null, $convert['valid_to'] ?? null)
            ->paginate($convert['per_page'] ?? 10)
            ->through(fn($plan) => [
                'id'            => $plan->id,
                'name'          => $plan->name,
                'description'   => $plan->description,
                'totalDuration' => $plan->total_duration,
                'regularPrice'  => $plan->regular_price,
                'sellingPrice'  => $plan->selling_price,
                'conditionType' => $plan->condition_type?->description(),
                'activeFlag'    => $plan->active_flag->description(),
                'sortOrder'     => $plan->sort_order,
                'validFrom'     => $plan->valid_from,
                'validTo'       => $plan->valid_to,
                'shop'          => $plan->shop->only(['id', 'name']),
                'menus'         => $plan->menus->map(fn($menu) => $menu->only(['id', 'name', 'type'])),
            ]);

        return [
            'filters'     => array_merge($filters, [
                'perPage' => (int) ($filters['perPage'] ?? 10),
            ]),
            'activeFlags' => ActiveFlagTypeEnum::options(),
            'menuTypes'   => MenuTypeEnum::options(),
            'plans'       => $plans->items(),
            'links'       => $plans->linkCollection(),
            'pagination'  => [
                'currentPage' => $plans->currentPage(),
                'lastPage'    => $plans->lastPage(),
                'prev'        => $plans->previousPageUrl(),
                'next'        => $plans->nextPageUrl(),
                'total'       => $plans->total(),
            ],
        ];
    }
}
