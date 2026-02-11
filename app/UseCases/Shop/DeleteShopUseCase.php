<?php

namespace App\UseCases\Shop;

use Illuminate\Support\Facades\DB;

class DeleteShopUseCase
{
    public function __invoke(Shop $shop): bool
    {
        return DB::transaction(function () use ($shop) {
            $shop->load(['businessHours', 'images']);
            $shop->businessHours->each->delete();
            $shop->images->each->delete();
            $shop->delete();

            return true;
        });
    }
}
