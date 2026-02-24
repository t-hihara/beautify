<?php

namespace Database\Seeders;

use App\Enum\ActiveFlagTypeEnum;
use App\Models\Shop;
use Carbon\Carbon;
use Database\Seeders\Definitions\PlanTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends BaseSeeder
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
        $plans    = PlanTemplate::getDefinitions();
        $planKeys = array_keys($plans);

        Shop::chunkById(self::CHUNK_SIZE, function ($shops) use (&$items, $plans, $planKeys) {
            foreach ($shops as $shop) {
                $numberOfPlans = random_int(5, 10);
                $selectedKeys  = $this->randomSelect($planKeys, $numberOfPlans);
                $sortOrder     = 1;

                foreach ($selectedKeys as $planKey) {
                    $plan = $plans[$planKey];
                    $items[] = [
                        'shop_id'        => $shop->id,
                        'name'           => $plan['name'],
                        'description'    => $plan['description'],
                        'duration'       => $plan['duration'],
                        'regular_price'  => $plan['regular_price'],
                        'selling_price'  => $plan['selling_price'],
                        'condition_type' => $plan['condition_type'],
                        'active_flag'    => ActiveFlagTypeEnum::ACTIVE->value,
                        'sort_order'     => $sortOrder++,
                        'valid_from'     => $plan['valid_from'] ?? null,
                        'valid_to'       => $plan['valid_to'] ?? null,
                        'created_at'     => $this->now,
                        'updated_at'     => $this->now,
                    ];
                }
            }
        });

        $this->insertData('plans', $items);

        $this->finalize('PlanSeeder', [
            'プラン数' => count($items),
        ]);
    }

    private function randomSelect(array $items, int $count): array
    {
        if ($count <= 0 || $count >= count($items)) {
            return $items;
        }

        $keys = array_rand($items, $count);
        $keys = is_array($keys) ? $keys : [$keys];

        return array_map(fn($key) => $items[$key], $keys);
    }
}
