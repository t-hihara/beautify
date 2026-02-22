<?php

namespace App\UseCases\Menu;

use App\Models\Menu;
use App\Utilities\RecursiveCovert;
use Illuminate\Support\Facades\DB;

class CreateMenuUseCase
{
    public function __construct(
        private Menu $menu,
    ) {}

    public function __invoke(array $payload): ?Menu
    {
        return DB::transaction(function () use ($payload) {
            $convert = RecursiveCovert::_convert($payload, 'snake');
            $this->menu->fill($convert)->save();

            return $this->menu;
        });
    }
}
