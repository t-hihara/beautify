<?php

namespace App\UseCases\Plan;

use App\Enum\ActiveFlagTypeEnum;
use App\Models\Plan;
use App\Models\Shop;
use App\Services\Media\UploadImageService;
use App\Utilities\RecursiveCovert;
use DomainException;
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
        $shop    = Shop::find($convert['shop_id']);

        if (!$shop) {
            throw new DomainException('指定された店舗が見つかりません。');
        }

        if (
            $shop
            && $convert['active_flag'] === ActiveFlagTypeEnum::ACTIVE->value
            && $shop->active_flag->value === ActiveFlagTypeEnum::INACTIVE->value
        ) {
            throw new DomainException('店舗が運営停止中のため、プランを有効にできません。');
        }

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
