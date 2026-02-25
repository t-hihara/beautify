<?php

namespace App\UseCases\Menu;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\MenuTypeEnum;
use App\Models\Shop;

class PrepareMenuCreateFormUseCase
{
    public function __invoke(?int $shopId = null): array
    {
        return [
            'activeFlags' => ActiveFlagTypeEnum::options(),
            'menuTypes'   => MenuTypeEnum::options(),
            'shops'       => Shop::byId($shopId)->get(['id', 'name']),
        ];
    }
}
