<?php

namespace App\UseCases\Shop;

use App\Models\Shop;
use App\Models\ShopBusinessHour;
use App\Models\ShopImage;
use App\Utilities\RecursiveCovert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateShopUseCase
{
    public function __invoke(array $payload, Shop $shop): Shop
    {
        return DB::transaction(function () use ($payload, $shop) {
            $convert       = RecursiveCovert::_convert($payload, 'snake');
            $shopData      = $convert['shop'];
            $businessHours = $shopData['business_hours'];
            $keepImageIds  = $shopData['keep_image_ids'];
            $newImages     = $shopData['new_images'];

            $shop->load(['businessHours', 'images']);

            unset($convert['updated_at']);
            $shop->fill($convert)->save();


            $rows = [];
            foreach ($businessHours as $businessHour) {
                $rows[] = [
                    'shop_id'     => $shop->id,
                    'day_of_week' => $businessHour['day_of_week'],
                    'open_time'   => $businessHour['open_time'],
                    'close_time'  => $businessHour['close_time'],
                ];
            }
            ShopBusinessHour::upsert(
                $rows,
                ['shop_id', 'day_of_week'],
                ['open_time', 'close_time'],
            );

            $shop->images->whereNotIn('id', $keepImageIds)
                ->each(function (ShopImage $shopImage): void {
                    if (str_starts_with($shopImage->file_path, 'shops/')) {
                        Storage::disk('s3')->delete($shopImage->file_path);
                    }
                    $shopImage->delete();
                });

            foreach ($newImages as $newImage) {
                $path = $newImage->store('shops/' . $shop->id, 's3');
                $shop->images()->create([
                    'file_path' => $path,
                    'filename'  => $newImage->getClientOriginalName(),
                    'mime_type' => $newImage->getMimeType(),
                    'file_size' => $newImage->getSize(),
                ]);
            }

            return $shop;
        });
    }
}
