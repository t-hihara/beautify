<?php

namespace App\UseCases\Menu;

use App\Models\Menu;
use App\Utilities\RecursiveCovert;
use Illuminate\Support\Facades\DB;

class UpdateMenuUseCase
{
    public function __invoke(array $payload, Menu $menu): Menu
    {
        return DB::transaction(function () use ($payload, $menu) {
            $convert = RecursiveCovert::_convert($payload, 'snake');
            $menu->fill($convert)->save();

            return $menu;
        });
    }
}
