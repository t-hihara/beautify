<?php

namespace App\UseCases\Operator\Menu;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\MenuTypeEnum;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Builder;

class PrepareMenuCreateFormUseCase
{
    public function __invoke(?int $shopId = null): array
    {
        return [
            'activeFlags' => ActiveFlagTypeEnum::options(),
            'menuTypes'   => MenuTypeEnum::options(),
            'shops'       => Shop::when($shopId, fn(Builder $query, $shopId) => $query->where('id', $shopId))
                ->get(['id', 'name']),
        ];
    }
}
