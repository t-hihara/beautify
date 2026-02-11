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

            return $this->shop;
        });
    }
}
