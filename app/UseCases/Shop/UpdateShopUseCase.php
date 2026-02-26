<?php

namespace App\UseCases\Shop;

use App\Models\Shop;
use App\Models\ShopBusinessHour;
use App\Models\UploadedImage;
use App\Utilities\RecursiveCovert;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateShopUseCase
{
    public function __invoke(array $payload, Shop $shop): Shop
    {
        return DB::transaction(function () use ($payload, $shop) {
            $convert           = RecursiveCovert::_convert($payload, 'snake');
            $shopData          = Arr::except($convert['shop'], ['business_hours', 'keep_image_ids', 'new_images']);
            $businessHoursData = $convert['shop']['business_hours'];
            $keepImageIds      = $convert['shop']['keep_image_ids'] ?? [];
            $newImagesData     = $convert['shop']['new_images'] ?? [];
            $imageDisk         = config('filesystems.default');

            $shop->load(['businessHours', 'images']);
            $shop->fill($shopData)->save();

            $shop->businessHours->each(function (ShopBusinessHour $businessHour) use ($businessHoursData): void {
                $submittedHour = collect($businessHoursData)->firstWhere('day_of_week', $businessHour->day_of_week);
                if ($submittedHour) {
                    $businessHour->open_time  = $submittedHour['open_time'];
                    $businessHour->close_time = $submittedHour['close_time'];
                    $businessHour->save();
                }
            });

            $shop->images->whereNotIn('id', $keepImageIds)
                ->each(function (UploadedImage $uploadedImage) use ($imageDisk): void {
                    if (str_starts_with($uploadedImage->file_path, 'shops/')) {
                        Storage::disk($imageDisk)->delete($uploadedImage->file_path);
                    }
                    $uploadedImage->delete();
                });

            foreach ($newImagesData as $newImage) {
                $path = $newImage->store('shops/' . $shop->id, $imageDisk);
                $shop->images()->create([
                    'disk'      => $imageDisk,
                    'file_path' => $path,
                    'file_name' => $newImage->getClientOriginalName(),
                    'mime_type' => $newImage->getMimeType(),
                    'file_size' => $newImage->getSize(),
                ]);
            }

            return $shop;
        });
    }
}
