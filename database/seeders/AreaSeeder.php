<?php

namespace Database\Seeders;

use App\Models\Prefecture;
use Carbon\Carbon;
use Database\Seeders\Definitions\AreaDefinition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaSeeder extends BaseSeeder
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

        $items = [];
        $areas = AreaDefinition::getDefinitions();

        Prefecture::whereIn('id', [11, 12, 13, 14])
            ->chunkById(self::CHUNK_SIZE, function ($prefectures) use (&$items, $areas) {
                foreach ($prefectures as $prefecture) {
                    if (!isset($areas[$prefecture->id])) {
                        return;
                    }

                    foreach ($areas[$prefecture->id] as $area) {
                        $items[] = [
                            'prefecture_id' => $prefecture->id,
                            'name'          => $area['name'],
                            'sort_order'    => $area['sort_order'],
                            'created_at'    => $this->now,
                            'updated_at'    => $this->now,
                        ];
                    }
                }
            });

        $this->insertData('areas', $items);

        $this->finalize('AreaSeeder', [
            'エリア数' => count($items),
        ]);
    }
}
