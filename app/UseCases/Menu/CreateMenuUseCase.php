<?php

namespace App\UseCases\Menu;

use App\Enum\ActiveFlagTypeEnum;
use App\Models\Menu;
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
        $menu->load('shop');

        if (
            $convert['active_flag'] === ActiveFlagTypeEnum::ACTIVE->value
            && $menu->shop->active_flag->value === ActiveFlagTypeEnum::INACTIVE->value
        ) {
            throw new DomainException('店舗が運営停止中のため、メニューを有効にできません。');
        }

        return DB::transaction(function () use ($convert) {
            $this->menu->fill($convert)->save();

            return $this->menu;
        });
    }
}
