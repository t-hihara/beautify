<?php

namespace App\UseCases\Shop;

use App\Models\Shop;
use App\Models\UploadedImage;
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
            $keepImageIds  = $shopData['keep_image_ids'] ?? [];
            $newImages     = $shopData['new_images'] ?? [];
            $imageDisk     = config('filesystems.default');

            $shop->load(['businessHours', 'images']);

            unset($convert['updated_at']);
            $shop->fill($convert)->save();

            $shop->businessHours->each(function ($model) use ($businessHours): void {
                $data = collect($businessHours)->firstWhere('day_of_week', $model->day_of_week);
                if ($data) {
                    $model->open_time  = $data['open_time'];
                    $model->close_time = $data['close_time'];
                    $model->save();
                }
            });

            $shop->images->whereNotIn('id', $keepImageIds)
                ->each(function (UploadedImage $uploadedImage) use ($imageDisk): void {
                    if (str_starts_with($uploadedImage->file_path, 'shops/')) {
                        Storage::disk($imageDisk)->delete($uploadedImage->file_path);
                    }
                    $uploadedImage->delete();
                });

            foreach ($newImages as $newImage) {
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
