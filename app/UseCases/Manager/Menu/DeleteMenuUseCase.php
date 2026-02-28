<?php

namespace App\UseCases\Manager\Menu;

use App\Models\Menu;
use DomainException;
use Illuminate\Support\Facades\DB;

class DeleteMenuUseCase
{
    public function __invoke(Menu $menu): bool
    {
        if ($menu->plans()->exists()) {
            throw new DomainException('プランに紐づいているため削除できません。');
        }

        return DB::transaction(function () use ($menu) {
            $menu->delete();
            return true;
        });
    }
}
