<?php

namespace App\UseCases\Shop;

use App\Models\Shop;
use Illuminate\Support\Facades\Storage;

class FetchShopPlansUseCase
{
    public function __construct(
        private readonly FetchShopUseCase $useCase,
    ) {}

    public function __invoke(Shop $shop): array
    {
        $data = ($this->useCase)($shop);
        $shop->load(['plans.image', 'plans.menus']);

        $plans = $shop->plans
            ->sortBy('sort_order')
            ->values()
            ->map(fn($plan) => [
                'id'              => $plan->id,
                'name'            => $plan->name,
                'duration'        => $plan->duration,
                'regularPrice'    => $plan->regular_price,
                'sellingPrice'    => $plan->selling_price,
                'conditionType'   => $plan->condition_type?->description(),
                'activeFlag'      => $plan->active_flag->description(),
                'validFrom'       => $plan->valid_from,
                'validTo'         => $plan->valid_to,
                'discountPercent' => $plan->regular_price > 0 && $plan->regular_price > $plan->selling_price
                    ? (int) round((1 - $plan->selling_price / $plan->regular_price) * 100)
                    : null,
                'menuTypes' => $plan->menus->map(fn($menu) => $menu->type->description()),
                'image'     => $plan->image ? [
                    'id'       => $plan->image->id,
                    'fileName' => $plan->image->file_name,
                    'filePath' => str_starts_with($plan->image->file_path, 'http')
                        ? $plan->image->file_path
                        : Storage::disk($plan->image->disk)->temporaryUrl($plan->image->file_path, now()->addMinutes(60)),
                ] : null,
            ]);

        return [
            'shop' => array_merge($data, ['plans' => $plans]),
        ];
    }
}
