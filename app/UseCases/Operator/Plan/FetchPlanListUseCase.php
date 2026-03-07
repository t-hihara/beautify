<?php

namespace App\UseCases\Operator\Plan;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\MenuTypeEnum;
use App\Models\Plan;
use App\Models\Shop;
use App\Utilities\RecursiveCovert;
use Illuminate\Database\Eloquent\Builder;

class FetchPlanListUseCase
{
    public function __invoke(array $filters, ?int $shopId = null): array
    {
        $convert = RecursiveCovert::_convert($filters, 'snake');
        if ($shopId) {
            $convert['shop_ids'] = [$shopId];
        }

        $plans = $this->queryWithFilters($convert)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate($convert['per_page'] ?? 10)
            ->withQueryString()
            ->through(fn($plan) => [
                'id'            => $plan->id,
                'name'          => $plan->name,
                'description'   => $plan->description,
                'duration'      => $plan->duration,
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

    private function queryWithFilters(array $convert): Builder
    {
        return Plan::with(['shop', 'menus'])
            ->when($convert['name'] ?? null, fn(Builder $query, $name) => $query->where('name', 'like', "%{$name}%"))
            ->when($convert['active_flag'] ?? null, fn(Builder $query, $flag) => $query->where('active_flag', $flag))
            ->when($convert['shop_ids'] ?? null, fn(Builder $query, $shopIds) => $query->whereIn('shop_id', $shopIds))
            ->when($convert['types'] ?? null, fn(Builder $query, $types) => $query->whereHas('menus', fn(Builder $q) => $q->whereIn('type', $types)))
            ->when(
                ($convert['valid_from'] ?? null) && ($convert['valid_to'] ?? null),
                fn(Builder $query, $_) => $query->where('valid_from', '>=', $convert['valid_from'])->where('valid_to', '<=', $convert['valid_to'])
            )
            ->when(
                ($convert['valid_from'] ?? null) && !($convert['valid_to'] ?? null),
                fn(Builder $query, $_) => $query->where('valid_from', '>=', $convert['valid_from'])
            )
            ->when(
                !($convert['valid_from'] ?? null) && ($convert['valid_to'] ?? null),
                fn(Builder $query, $_) => $query->where('valid_to', '<=', $convert['valid_to'])
            );
    }
}
