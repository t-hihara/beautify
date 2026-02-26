<?php

namespace Database\Seeders;

use App\Models\Area;
use Carbon\Carbon;
use Database\Seeders\Definitions\StationDefinition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StationSeeder extends BaseSeeder
{
    private Carbon $now;
    private const CHUNK_SIZE = 1000;

    public function __construct()
    {
        $this->now = now();
    }

    public function run(): void
    {
        $this->initialize();

        $items    = [];
        $stations = StationDefinition::getDefinitions();

        Area::whereIn('id', array_keys($stations))
            ->orderBy('prefecture_id')
            ->orderBy('sort_order')
            ->chunkById(self::CHUNK_SIZE, function ($areas) use (&$items, $stations) {
                foreach ($areas as $area) {
                    if (!isset($stations[$area->prefecture_id][$area->name])) {
                        continue;
                    }

                    foreach ($stations[$area->prefecture->id][$area->name] as $station) {
                        $items[] = [
                            'prefecture_id' => $area->prefecture_id,
                            'area_id'       => $area->id,
                            'name'          => $station['name'],
                            'sort_order'    => $station['sort_order'],
                            'created_at'    => $this->now,
                            'updated_at'    => $this->now,
                        ];
                    }
                }
            });

        $this->insertData('stations', $items);

        $this->finalize('StationSeeder', [
            '駅数' => count($items),
        ]);
    }
}
