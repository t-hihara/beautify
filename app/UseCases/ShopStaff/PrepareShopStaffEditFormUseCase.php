<?php

namespace App\UseCases\ShopStaff;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\ShopStaffPositionTypeEnum;
use App\Models\ShopStaff;
use Illuminate\Support\Facades\Storage;

class PrepareShopStaffEditFormUseCase
{
    public function __invoke(ShopStaff $staff): array
    {
        $staff->load(['shop', 'image']);

        return [
            'staff' => [
                'id'              => $staff->id,
                'lastName'        => $staff->last_name,
                'firstName'       => $staff->first_name,
                'email'           => $staff->email,
                'position'        => $staff->position->value,
                'experienceYears' => $staff->experience_years,
                'description'     => $staff->description,
                'activeFlag'      => $staff->active_flag->value,
                'image'           => $staff->image ? [
                    'id'       => $staff->image->id,
                    'fileName' => $staff->image->file_name,
                    'filePath' => str_starts_with($staff->image->file_path, 'http')
                        ? $staff->image->file_path
                        : Storage::disk($staff->image->disk)->temporaryUrl($staff->image->file_path, now()->addMinutes(60)),
                ] : null,
            ],
            'activeFlags' => ActiveFlagTypeEnum::options(),
            'positions'   => ShopStaffPositionTypeEnum::options(),
            'shops'       => [],
        ];
    }
}
