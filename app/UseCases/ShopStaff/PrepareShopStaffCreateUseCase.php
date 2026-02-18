<?php

namespace App\UseCases\ShopStaff;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\ShopStaffPositionTypeEnum;
use App\Models\Shop;

class PrepareShopStaffCreateUseCase
{
    public function __invoke(): array
    {
        return [
            'shops'       => Shop::get(['id', 'name']),
            'activeFlags' => ActiveFlagTypeEnum::options(),
            'positions'   => ShopStaffPositionTypeEnum::options(),
        ];
    }
}
