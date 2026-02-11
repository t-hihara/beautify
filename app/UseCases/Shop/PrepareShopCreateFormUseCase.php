<?php

namespace App\UseCases\Shop;

use App\Enum\ActiveFlagTypeEnum;
use App\Models\Prefecture;

class PrepareShopCreateFormUseCase
{
    public function __invoke(): array
    {
        return [
            'prefectures' => Prefecture::get(['id', 'name']),
            'activeFlags'  => ActiveFlagTypeEnum::options(),
        ];
    }
}
