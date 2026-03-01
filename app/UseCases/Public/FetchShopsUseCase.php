<?php

namespace App\UseCases\Public;

use App\Enum\ActiveFlagTypeEnum;
use App\Models\Shop;
use Illuminate\Support\Facades\Storage;

class FetchShopsUseCase
{
    public function __invoke(array $filters): array
    {
        $shops = Shop::with(['station', 'plans' . 'mainImage'])
            ->paginate(20)
            ->through(fn($shop) => [
                'id'          => $shop->id,
                'name'        => $shop->name,
                'description' => $shop->description,
                'mainImage'   => $shop->mainImage ? [
                    'id'       => $shop->mainImage->id,
                    'fileName' => $shop->mainImage->file_name,
                    'filePath' => str_starts_with($shop->mainImage->file_path, 'http')
                        ? $shop->mainImage->file_path
                        : Storage::disk($shop->mainImage->disk)->temporaryUrl($shop->mainImage->file_path, now()->addMinutes(60)),
                ] : null,
                'plans' => $shop->plans
                    ->filter(fn($plan) => $plan->active_flag === ActiveFlagTypeEnum::ACTIVE)
                    ->sortBy('sort_order')
                    ->sortBy('id')
                    ->map(fn($plan) => [
                        'id'            => $plan->id,
                        'name'          => $plan->name,
                        'duration'      => $plan->duration,
                        'regularPrice'  => $plan->regular_price,
                        'sellingPrice'  => $plan->selling_price,
                        'conditionType' => $plan->condition_type?->description(),
                        'validFrom'     => $plan->valid_from,
                        'validTo'       => $plan->valid_to,
                    ]),
            ]);

        return [
            'shops'      => $shops->items(),
            'links'      => $shops->linkCollection(),
            'pagination' => [
                'currentPage' => $shops->currentPage(),
                'lastPage'    => $shops->lastPage(),
                'prev'        => $shops->previousPageUrl(),
                'next'        => $shops->nextPageUrl(),
                'total'       => $shops->total(),
            ],
        ];
    }
}
