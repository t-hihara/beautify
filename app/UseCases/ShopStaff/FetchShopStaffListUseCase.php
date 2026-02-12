<?php

namespace App\UseCases\ShopStaff;

use App\Enum\ActiveFlagTypeEnum;
use App\Models\Shop;
use App\Models\ShopStaff;
use Illuminate\Support\Facades\Storage;

class FetchShopStaffListUseCase
{
    public function __invoke(array $filters): array
    {
        $staffs = ShopStaff::with(['image', 'shop'])
            ->orderBy('id')
            ->paginate(20)
            ->through(fn($staff) => [
                'id'              => $staff->id,
                'name'            => $staff->name,
                'email'           => $staff->email,
                'position'        => $staff->position->description(),
                'description'     => $staff->description,
                'experienceYears' => $staff->experience_years,
                'activeFlag'      => $staff->active_flag->description(),
                'shop'            => [
                    'id'   => $staff->shop->id,
                    'name' => $staff->shop->name,
                ],
                'image'           => $staff->image ? [
                    'id'       => $staff->image->id,
                    'fileName' => $staff->image->file_name,
                    'filePath' => str_starts_with($staff->image->file_path, 'http')
                        ? $staff->image->file_path
                        : Storage::disk($image->disk)->temporaryUrl($staff->image->file_path, now()->addMinutes(60)),
                ] : null,
            ]);

        return [
            'shops'       => Shop::get(['id', 'name']),
            'activeFlags' => ActiveFlagTypeEnum::options(),
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
