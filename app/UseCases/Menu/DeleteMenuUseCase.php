<?php

namespace App\UseCases\Menu;

use Illuminate\Support\Facades\DB;

class DeleteMenuUseCase
{
    public function __invoke(Menu $menu): bool
    {
        return DB::transaction(function () use ($menu) {
            $menu->delete();
            return true;
        });
    }
}
