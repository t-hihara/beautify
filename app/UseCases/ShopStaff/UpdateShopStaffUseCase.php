<?php

namespace App\UseCases\ShopStaff;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\ShopStaffPositionTypeEnum;
use App\Models\ShopStaff;
use App\Services\Media\UploadImageService;
use App\Utilities\RecursiveCovert;
use DomainException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UpdateShopStaffUseCase
{
    public function __construct(
        private readonly UploadImageService $imageService,
    ) {}

    public function __invoke(array $payload, ShopStaff $staff): ShopStaff
    {
        $convert = RecursiveCovert::_convert($payload, 'snake');
        $staff->load('shop');

        if (
            $convert['active_flag'] === ActiveFlagTypeEnum::ACTIVE->value
            && $plan->shop->active_flag->value === ActiveFlagTypeEnum::INACTIVE->value
        ) {
            throw new DomainException('店舗が運営停止中のため、プランを有効にできません。');
        }

        return DB::transaction(function () use ($convert, $staff) {
            $role = in_array($convert['position'], [ShopStaffPositionTypeEnum::MANAGER->value, ShopStaffPositionTypeEnum::SALON_MANAGER->value], true)
                ? 'staff_owner'
                : 'staff';
            $staff->fill(Arr::except($convert, 'image'))->save();
            $staff->user->syncRoles(Role::findByName($role, 'shop'));

            $image = $convert['image'] ?? null;
            if ($image instanceof UploadedFile) {
                $this->imageService->attach($imageData, $this->plan, 'shop-staffs');
            }
            return $staff;
        });
    }
}
