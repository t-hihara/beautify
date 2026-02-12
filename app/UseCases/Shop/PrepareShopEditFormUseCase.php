<?php

namespace App\UseCases\Shop;

use App\Enum\ActiveFlagTypeEnum;
use App\Models\Prefecture;
use App\Models\Shop;
use Illuminate\Support\Facades\Storage;

class PrepareShopEditFormUseCase
{
    public function __invoke(Shop $shop): array
    {
        $shop->load(['businessHours', 'images']);
        return [
            'shop' => [
                'id'            => $shop->id,
                'name'          => $shop->name,
                'email'         => $shop->email,
                'phone'         => $shop->phone,
                'prefectureId'  => $shop->prefecture_id,
                'zipcode'       => $shop->zipcode,
                'address'       => $shop->address,
                'building'      => $shop->building,
                'description'   => $shop->description,
                'activeFlag'    => $shop->active_flag->value,
                'updatedAt'     => $shop->updated_at,
                'businessHours' => $shop->businessHours->map(fn($businessHour) => [
                    'id'        => $businessHour->id,
                    'dayOfWeek' => $businessHour->day_of_week->value,
                    'label'     => $businessHour->day_of_week->description(),
                    'openTime'  => $businessHour->open_time,
                    'closeTime' => $businessHour->close_time,
                ]),
                'images' => $shop->images->map(fn($image) => [
                    'id'       => $image->id,
                    'fileName' => $image->file_name,
                    'filePath' => str_starts_with($image->file_path, 'http')
                        ? $image->file_path
                        : Storage::disk($image->disk)->temporaryUrl($image->file_path, now()->addMinutes(60)),
                ]),
            ],
            'activeFlags' => ActiveFlagTypeEnum::options(),
            'prefectures' => Prefecture::get(['id', 'name']),
        ];
    }
}
