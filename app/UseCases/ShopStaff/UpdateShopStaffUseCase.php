<?php

namespace App\UseCases\ShopStaff;

use App\Enum\ShopStaffPositionTypeEnum;
use App\Models\ShopStaff;
use App\Services\Media\UploadImageService;
use App\Utilities\RecursiveCovert;
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
        return DB::transaction(function () use ($payload, $staff) {
            $convert = RecursiveCovert::_convert($payload, 'snake');
            $role    = in_array($convert['position'], [ShopStaffPositionTypeEnum::MANAGER->value, ShopStaffPositionTypeEnum::SALON_MANAGER->value], true)
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
