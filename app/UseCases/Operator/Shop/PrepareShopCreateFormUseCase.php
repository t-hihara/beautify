<?php

namespace App\UseCases\Operator\Shop;

use App\Enum\ActiveFlagTypeEnum;
use App\Models\Area;
use App\Models\Prefecture;
use App\Models\Station;

class PrepareShopCreateFormUseCase
{
    public function __invoke(): array
    {
        return [
            'prefectures' => Prefecture::get(['id', 'name']),
            'activeFlags' => ActiveFlagTypeEnum::options(),
            'areas'       => Area::get(['id', 'name', 'prefecture_id'])->map(fn($area) => [
                'id'           => $area->id,
                'name'         => $area->name,
                'prefectureId' => $area->prefecture_id,
            ]),
            'stations'    => Station::get(['id', 'name', 'area_id'])->map(fn($station) => [
                'id'     => $station->id,
                'name'   => $station->name,
                'areaId' => $station->area_id,
            ]),
        ];
    }
}
