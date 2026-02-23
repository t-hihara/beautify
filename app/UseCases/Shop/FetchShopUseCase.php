<?php

namespace App\UseCases\Shop;

use App\Enum\ActiveFlagTypeEnum;
use App\Models\Shop;
use Illuminate\Support\Facades\Storage;

class FetchShopUseCase
{
    public function __invoke(Shop $shop): array
    {
        $shop->load(['prefecture']);

        return [
            'id'          => $shop->id,
            'name'        => $shop->name,
            'email'       => $shop->email,
            'prefecture'  => $shop->prefecture->name,
            'activeFlag'  => $shop->active_flag->description(),
        ];
    }
}
