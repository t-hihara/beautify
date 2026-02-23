<?php

namespace App\UseCases\ShopStaff;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\ShopStaffPositionTypeEnum;
use App\Models\Shop;
use App\Models\ShopStaff;
use App\Utilities\RecursiveCovert;
use Illuminate\Support\Facades\Storage;

class FetchShopStaffListUseCase
{
    public function __invoke(array $filters, ?int $shopId = null): array
    {
        $convert = RecursiveCovert::_convert($filters, 'snake');
        $shopId ? $convert['shop_ids'] = [$shopId] : null;

        $staffs = ShopStaff::with(['image', 'shop'])
            ->byName($convert['name'] ?? null)
            ->byShopIds($convert['shop_ids'] ?? null)
            ->byActiveFlag($convert['active_flag'] ?? null)
            ->byPositions($convert['positions'] ?? null)
            ->orderBy('id')
            ->paginate($convert['per_page'] ?? 10)
            ->through(fn($staff) => [
                'id'              => $staff->id,
                'name'            => $staff->name,
                'email'           => $staff->email,
                'position'        => $staff->position->description(),
                'description'     => $staff->description,
                'experienceYears' => $staff->experience_years,
                'activeFlag'      => $staff->active_flag->description(),
                'shop'            => $staff->shop->only(['id', 'name']),
                'image' => $staff->image ? [
                    'id'       => $staff->image->id,
                    'fileName' => $staff->image->file_name,
                    'filePath' => str_starts_with($staff->image->file_path, 'http')
                        ? $staff->image->file_path
                        : Storage::disk($staff->image->disk)->temporaryUrl($staff->image->file_path, now()->addMinutes(60)),
                ] : null,
            ]);

        return [
            'filters'     => array_merge($filters, [
                'perPage' => (int) ($filters['perPage'] ?? 10),
            ]),
            'shops'       => Shop::get(['id', 'name']),
            'activeFlags' => ActiveFlagTypeEnum::options(),
            'positions'   => ShopStaffPositionTypeEnum::options(),
            'shopStaffs'  => $staffs->items(),
            'links'       => $staffs->linkCollection(),
            'pagination'  => [
                'currentPage' => $staffs->currentPage(),
                'lastPage'    => $staffs->lastPage(),
                'prev'        => $staffs->previousPageUrl(),
                'next'        => $staffs->nextPageUrl(),
                'total'       => $staffs->total(),
            ],
        ];
    }
}
