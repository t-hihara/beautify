<?php

namespace App\UseCases\Public;

use App\Models\Prefecture;

class FetchHomeUseCase
{
    public function __invoke(): array
    {
        $prefectures = Prefecture::with(['areas'])
            ->orderBy('id')
            ->get()
            ->map(fn($prefecture) => [
                'id'    => $prefecture->id,
                'name'  => $prefecture->name,
                'areas' => $prefecture->areas
                    ->sortBy('sort_order')
                    ->map(fn($area) => [
                        'id'   => $area->id,
                        'name' => $area->name,
                    ]),
            ]);

        [$prefecturesWithAreas, $prefecturesWithoutAreas] = $prefectures->partition(fn($prefecture) => $prefecture['areas']->isNotEmpty());

        return [
            'prefecturesWithAreas'    => $prefecturesWithAreas->values()->all(),
            'prefecturesWithoutAreas' => $prefecturesWithoutAreas->values()->all(),
        ];
    }
}
