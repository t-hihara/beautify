<?php

namespace App\UseCases\Plan;

use App\Models\Plan;
use App\Services\Media\UploadImageService;
use App\Utilities\RecursiveCovert;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CreatePlanUseCase
{
    public function __construct(
        private Plan $plan,
        private readonly UploadImageService $imageService,
    ) {}

    public function __invoke(array $payload): ?Plan
    {
        $convert = RecursiveCovert::_convert($payload, 'snake');

        return DB::transaction(function () use ($convert) {
            $planData  = Arr::except($convert, ['image', 'menu_ids']);
            $imageData = $convert['image'];
            $menuIds   = $convert['menu_ids'];

            $this->plan->fill($planData)->save();
            $this->plan->menus()->sync($menuIds);

            if ($imageData instanceof UploadedFile) {
                $this->imageService->attach($imageData, $this->plan, 'plans');
            }
            return $this->plan;
        });
    }
}
