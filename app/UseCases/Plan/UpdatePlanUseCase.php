<?php

namespace App\UseCases\Plan;

use App\Models\Plan;
use App\Services\Media\UploadImageService;
use App\Utilities\RecursiveCovert;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UpdatePlanUseCase
{
    public function __construct(
        private readonly UploadImageService $imageService,
    ) {}

    public function __invoke(array $payload, Plan $plan): Plan
    {
        $convert = RecursiveCovert::_convert($payload, 'snake');

        return DB::transaction(function () use ($convert, $plan) {
            $planData  = Arr::except($convert, ['image', 'menu_ids']);
            $imageData = $convert['image'] ?? null;
            $menuIds   = $convert['menu_ids'];

            $plan->fill($planData)->save();
            $plan->menus()->sync($menuIds);

            if ($imageData instanceof UploadedFile) {
                $this->imageService->attach($imageData, $plan, 'plans');
            }

            return $plan;
        });
    }
}
