<?php

namespace Database\Seeders;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\PlanConditionTypeEnum;
use App\Models\Shop;
use Carbon\Carbon;
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
        $plans    = $this->getPlans();
        $planKeys = array_keys($plans);

        Shop::chunkById(self::CHUNK_SIZE, function ($shops) use (&$items, $plans, $planKeys) {
            foreach ($shops as $shop) {
                $numberOfPlans = random_int(1, 2);
                $selectedKeys  = $this->randomSelect($planKeys, $numberOfPlans);
                $sortOrder     = 1;

                foreach ($selectedKeys as $planKey) {
                    $plan = $plans[$planKey];
                    $items[] = [
                        'shop_id'        => $shop->id,
                        'name'           => $plan['name'],
                        'description'    => $plan['description'],
                        'total_duration' => $plan['total_duration'],
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
            'プラン数' . count($items),
        ]);
    }

    private function getPlans(): array
    {
        return [
            'cut_color' => [
                'name'           => 'カット＋カラー',
                'description'    => 'カットとカラーがセットになったお得なプランです。',
                'total_duration' => 135,
                'regular_price'  => 12000,
                'selling_price'  => 10000,
                'condition_type' => PlanConditionTypeEnum::FIRST_VISIT->value,
                'valid_from'     => null,
                'valid_to'       => null,
            ],
            'cut_color_treatment' => [
                'name'           => 'カット＋カラー＋トリートメント',
                'description'    => 'カット・カラー・トリートメントのフルセットプラン。',
                'total_duration' => 165,
                'regular_price'  => 16000,
                'selling_price'  => 13000,
                'condition_type' => PlanConditionTypeEnum::WEEKDAY->value,
                'valid_from'     => null,
                'valid_to'       => null,
            ],
            'cut_perm' => [
                'name'           => 'カット＋パーマ',
                'description'    => 'カットとパーマのセットプラン。',
                'total_duration' => 135,
                'regular_price'  => 13000,
                'selling_price'  => 11000,
                'condition_type' => null,
                'valid_from'     => null,
                'valid_to'       => null,
            ],
            'full_care' => [
                'name'           => 'フルケアプラン',
                'description'    => 'カット・カラー・トリートメント・ヘッドスパの総合プラン。',
                'total_duration' => 210,
                'regular_price'  => 22000,
                'selling_price'  => 18000,
                'condition_type' => PlanConditionTypeEnum::PERIOD->value,
                'valid_from'     => $this->now->copy()->startOfMonth()->format('Y-m-d'),
                'valid_to'       => $this->now->copy()->endOfMonth()->format('Y-m-d'),
            ],
        ];
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
