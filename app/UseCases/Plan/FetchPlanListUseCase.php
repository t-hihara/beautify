<?php

namespace App\UseCases\Plan;

use App\Models\Plan;

class FetchPlanListUseCase
{
    public function __invoke(array $filters, ?int $shopId = null): array
    {
        $plans = Plan::paginate(20)
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
            ]);

        return [
            'plans'      => $plans->items(),
            'links'      => $plans->linkCollection(),
            'pagination' => [
                'currentPage' => $plans->currentPage(),
                'lastPage'    => $plans->lastPage(),
                'prev'        => $plans->previousPageUrl(),
                'next'        => $plans->nextPageUrl(),
                'total'       => $plans->total(),
            ],
        ];
    }
}
