<?php

namespace App\UseCases\Operator\Shop;

use App\Enum\ActiveFlagTypeEnum;
use App\Models\Shop;
use Illuminate\Support\Facades\Storage;

class FetchShopDetailTopUseCase
{
    public function __construct(
        private readonly FetchShopUseCase $useCase,
    ) {}

    public function __invoke(Shop $shop): array
    {
        $data = ($this->useCase)($shop);
        $shop->load(['prefecture', 'images', 'staffs.image', 'plans.menus', 'plans.image']);

        $images = $shop->images->map(fn($image) => [
            'id'       => $image->id,
            'filePath' => str_starts_with($image->file_path, 'http')
                ? $image->file_path
                : Storage::disk($image->disk)->temporaryUrl($image->file_path, now()->addMinutes(60)),
            'fileName' => $image->file_name,
        ]);

        $staffs = $shop->staffs
            ->sortBy('id')
            ->filter(fn($staff) => $staff->active_flag === ActiveFlagTypeEnum::ACTIVE)
            ->take(4)
            ->values()
            ->map(fn($staff) => [
                'id'          => $staff->id,
                'name'        => $staff->name,
                'position'    => $staff->position->description(),
                'description' => $staff->description,
                'image'       => $staff->image ? [
                    'id'       => $staff->image->id,
                    'fileName' => $staff->image->file_name,
                    'filePath' => str_starts_with($staff->image->file_path, 'http')
                        ? $staff->image->file_path
                        : Storage::disk($staff->image->disk)->temporaryUrl($staff->image->file_path, now()->addMinutes(60)),
                ] : null,
            ]);

        $plans = $shop->plans
            ->sortBy('sort_order')
            ->filter(fn($plan) => $plan->active_flag === ActiveFlagTypeEnum::ACTIVE)
            ->take(4)
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
            'shop' => array_merge($data, [
                'phone'       => $shop->phone,
                'zipcode'     => $shop->zipcode,
                'address'     => $shop->address,
                'building'    => $shop->building,
                'description' => $shop->description,
                'activeFlag'  => $shop->active_flag->description(),
                'staffCount'  => $shop->staffs->count(),
                'images'      => $images,
                'staffs'      => $staffs,
                'plans'       => $plans,
            ]),
        ];
    }
}
