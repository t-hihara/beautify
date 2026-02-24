<?php

namespace App\UseCases\Plan;

use App\Models\Plan;
use App\Utilities\RecursiveCovert;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UpdatePlanUseCase
{
    public function __invoke(array $payload, Plan $plan): Plan
    {
        $convert = RecursiveCovert::_convert($payload, 'snake');

        return DB::transaction(function () use ($convert, $plan) {
            $planData = Arr::except($convert, ['image', 'menu_ids']);
            $menuIds  = $convert['menu_ids'];

            $plan->fill($planData)->save();
            $plan->menus()->sync($menuIds);

            return $plan;
        });
    }
}
