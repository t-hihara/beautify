<?php

namespace App\UseCases\Manager\Plan;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\PlanConditionTypeEnum;
use App\Models\Menu;
use App\Models\Plan;
use Illuminate\Support\Facades\Storage;

class PreparePlanEditFormUseCase
{
    public function __invoke(Plan $plan): array
    {
        $plan->load(['image', 'menus']);
        return [
            'plan' => [
                'id'            => $plan->id,
                'shopId'        => $plan->shop_id,
                'name'          => $plan->name,
                'description'   => $plan->description,
                'duration'      => $plan->duration,
                'regularPrice'  => $plan->regular_price,
                'sellingPrice'  => $plan->selling_price,
                'conditionType' => $plan->condition_type?->value,
                'activeFlag'    => $plan->active_flag->value,
                'sortOrder'     => $plan->sort_order,
                'validFrom'     => $plan->valid_from,
                'validTo'       => $plan->valid_to,
                'image'         => $plan->image ? [
                    'id'       => $plan->image->id,
                    'fileName' => $plan->image->file_name,
                    'filePath' => str_starts_with($plan->image->file_path, 'http')
                        ? $plan->image->file_path
                        : Storage::disk($plan->image->disk)->temporaryUrl($plan->image->file_path, now()->addMinutes(60)),
                ] : null,
                'menus' => $plan->menus
                    ->sortBy('sort_order')
                    ->map(fn($menu) => [
                        'id'       => $menu->id,
                        'name'     => $menu->name,
                        'type'     => $menu->type->description(),
                        'price'    => $menu->price,
                        'duration' => $menu->duration,
                    ]),
            ],
            'shops'          => [],
            'activeFlags'    => ActiveFlagTypeEnum::options(),
            'conditionTypes' => PlanConditionTypeEnum::options(),
            'menus'          => Menu::byShopId($plan->shop_id)
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
