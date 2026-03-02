<?php

namespace App\UseCases\Manager\ShopStaff;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\ShopStaffPositionTypeEnum;
use App\Models\Shop;
use App\Models\ShopStaff;
use App\Utilities\RecursiveCovert;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class FetchShopStaffListUseCase
{
    public function __invoke(array $filters, ?int $shopId = null): array
    {
        $convert = RecursiveCovert::_convert($filters, 'snake');
        if ($shopId) {
            $convert['shop_ids'] = [$shopId];
        }

        $staffs = $this->queryWithFilters($convert)
            ->orderBy('id')
            ->paginate($convert['per_page'] ?? 10)
            ->withQueryString()
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

    private function queryWithFilters(array $convert): Builder
    {
        return ShopStaff::with(['image', 'shop'])
            ->when($convert['name'] ?? null, fn(Builder $query, $name) => $query->where('name', 'like', "%$name%"))
            ->when($convert['shop_ids'] ?? null, fn(Builder $query, $shopIds) => $query->whereIn('shop_id', $shopIds))
            ->when($convert['active_flag'] ?? null, fn(Builder $query, $flag) => $query->where('active_flag', $flag))
            ->when($convert['positions'] ?? null, fn(Builder $query, $positions) => $query->whereIn('position', $positions));
    }
}
