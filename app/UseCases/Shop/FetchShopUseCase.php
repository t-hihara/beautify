<?php

namespace App\UseCases\Shop;

use App\Enum\ActiveFlagTypeEnum;
use App\Models\Shop;
use Illuminate\Support\Facades\Storage;

class FetchShopUseCase
{
    public function __invoke(Shop $shop): array
    {
        $shop->load(['prefecture', 'images', 'staffs.image']);

        return [
            'shop' => [
                'id'          => $shop->id,
                'name'        => $shop->name,
                'email'       => $shop->email,
                'phone'       => $shop->phone,
                'prefecture'  => $shop->prefecture->name,
                'zipcode'     => $shop->zipcode,
                'address'     => $shop->address,
                'building'    => $shop->building,
                'description' => $shop->description,
                'activeFlag'  => $shop->active_flag->description(),
                'images'      => $shop->images->map(fn($image) => [
                    'id'       => $image->id,
                    'filePath' => str_starts_with($image->file_path, 'http')
                        ? $image->file_path
                        : Storage::disk($image->disk)->temporaryUrl($image->file_path, now()->addMinutes(60)),
                    'fileName' => $image->file_name,
                ]),
                'staffs' => $shop->staffs
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
                    ]),
                'staffCount' => $shop->staffs->count(),
            ]
        ];
    }
}
