<?php

namespace App\UseCases\Menu;

use App\Enum\ActiveFlagTypeEnum;
use App\Models\Menu;
use App\Models\Shop;
use App\Utilities\RecursiveCovert;
use DomainException;
use Illuminate\Support\Facades\DB;

class CreateMenuUseCase
{
    public function __construct(
        private Menu $menu,
    ) {}

    public function __invoke(array $payload): ?Menu
    {
        $convert = RecursiveCovert::_convert($payload, 'snake');
        $shop    = Shop::find($convert['shop_id']);

        if (!$shop) {
            throw new DomainException('指定された店舗が見つかりません。');
        }

        if (
            $shop
            && $convert['active_flag'] === ActiveFlagTypeEnum::ACTIVE->value
            && $shop->active_flag->value === ActiveFlagTypeEnum::INACTIVE->value
        ) {
            throw new DomainException('店舗が運営停止中のため、メニューを有効にできません。');
        }

        return DB::transaction(function () use ($convert) {
            $this->menu->fill($convert)->save();

            return $this->menu;
        });
    }
}
