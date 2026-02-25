<?php

namespace App\UseCases\ShopStaff;

use App\Enum\ShopStaffPositionTypeEnum;
use App\Models\ShopStaff;
use App\Models\User;
use App\Services\Media\UploadImageService;
use App\Utilities\RecursiveCovert;
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
        return DB::transaction(function () use ($payload) {
            $convert   = RecursiveCovert::_convert($payload, 'snake');
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
                $this->imageService->attach($imageData, $this->plan, 'shop-staffs');
            }

            return $staff;
        });
    }
}
