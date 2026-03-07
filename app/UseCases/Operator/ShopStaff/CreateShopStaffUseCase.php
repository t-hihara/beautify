<?php

namespace App\UseCases\Operator\ShopStaff;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\ShopStaffPositionTypeEnum;
use App\Models\Shop;
use App\Models\ShopStaff;
use App\Models\User;
use App\Services\Media\UploadImageService;
use App\Utilities\RecursiveCovert;
use DomainException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class CreateShopStaffUseCase
{
    public function __construct(
        private User $user,
        private readonly UploadImageService $imageService,
    ) {}

    public function __invoke(array $payload): ?ShopStaff
    {
        $convert = RecursiveCovert::_convert($payload, 'snake');
        $shop    = Shop::find($convert['shop_id']);

        if (!$shop) {
            throw new DomainException('指定された店舗が見つかりません。');
        }

        if (
            $convert['active_flag'] === ActiveFlagTypeEnum::ACTIVE->value
            && $shop->active_flag->value === ActiveFlagTypeEnum::INACTIVE->value
        ) {
            throw new DomainException('店舗が運営停止中のため、スタッフを有効にできません。');
        }

        return DB::transaction(function () use ($convert) {
            $userData  = Arr::except($convert, ['position', 'experience_years', 'image', 'description']);
            $staffData = Arr::except($convert, ['password', 'image']);
            $role      = in_array($convert['position'], [ShopStaffPositionTypeEnum::MANAGER->value, ShopStaffPositionTypeEnum::SALON_MANAGER->value], true)
                ? 'staff_owner'
                : 'staff';

            $this->user->fill($userData)->save();
            $this->user->assignRole(Role::findByName($role, 'shop'));
            $staff = $this->user->shopStaff()->create($staffData);

            $image = $convert['image'] ?? null;
            if ($image instanceof UploadedFile) {
                $this->imageService->attach($image, $staff, 'shop-staffs');
            }

            return $staff;
        });
    }
}
