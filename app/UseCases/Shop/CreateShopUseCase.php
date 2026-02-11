<?php

namespace App\UseCases\Shop;

use App\Models\Shop;
use App\Utilities\RecursiveCovert;
use Illuminate\Support\Facades\DB;

class CreateShopUseCase
{
    public function __construct(
        private Shop $shop,
    ) {}

    public function __invoke(array $validated): ?Shop
    {
        return DB::transaction(function () use ($validated) {
            $convert  = RecursiveCovert::_convert($validated, 'snake');
            $shopData = $convert['shop'];

            $this->shop->fill($shopData)->save();
            $this->shop->businessHours()->createMany($shopData['business_hours']);

            foreach ($shopData['new_images'] as $newImage) {
                $path = $newImage->store('shops/' . $this->shop->id, 's3');
                $this->shop->images()->create([
                    'file_path' => $path,
                    'filename'  => $newImage->getClientOriginalName(),
                    'mime_type' => $newImage->getMimeType(),
                    'file_size' => $newImage->getSize(),
                ]);
            }

            return $this->shop;
        });
    }
}
