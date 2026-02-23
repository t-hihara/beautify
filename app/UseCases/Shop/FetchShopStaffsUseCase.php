<?php

namespace App\UseCases\Shop;

use App\Enum\ActiveFlagTypeEnum;
use App\Models\Shop;
use Illuminate\Support\Facades\Storage;

class FetchShopStaffsUseCase
{
    public function __construct(
        private readonly FetchShopUseCase $useCase
    ) {}

    public function __invoke(Shop $shop): array
    {
        $data = ($this->useCase)($shop);
        $shop->load(['staffs.image']);

        $staffs = $shop->staffs
            ->filter(fn($staff) => $staff->active_flag === ActiveFlagTypeEnum::ACTIVE)
            ->values()
            ->map(fn($staff) => [
                'id'          => $staff->id,
                'name'        => $staff->name,
                'position'    => $staff->position->description(),
                'description' => $staff->description,
                'image'       => $staff->image ? [
                    'id'       => $staff->image->id,
                    'fileName' => $staff->image->file_name,
                    'filePath' => str_starts_with($staff->image->file_path, 'http')
                        ? $staff->image->file_path
                        : Storage::disk($staff->image->disk)->temporaryUrl($staff->image->file_path, now()->addMinutes(60)),
                ] : null,
            ]);

        return [
            'shop' => array_merge($data, ['staffs' => $staffs->all()]),
        ];
    }
}
