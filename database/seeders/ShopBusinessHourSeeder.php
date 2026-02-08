<?php

namespace Database\Seeders;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\DayOfWeekTypeEnum;
use App\Models\Shop;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShopBusinessHourSeeder extends BaseSeeder
{
    private const CHUNK_SIZE = 1000;
    private Carbon $now;

    public function __construct()
    {
        $this->now = now();
    }

    public function run(): void
    {
        $this->initialize();

        $items   = [];
        $presets = self::presets();
        $days    = DayOfWeekTypeEnum::cases();

        Shop::chunkById(self::CHUNK_SIZE, function ($shops) use (&$items, $presets, $days) {
            foreach ($shops as $shop) {
                $preset = $shop->active_flag === ActiveFlagTypeEnum::ACTIVE
                    ? $presets[$shop->id % count($presets)]
                    : self::presetAllNull();

                foreach ($days as $i => $day) {
                    $time = $preset[$i] ?? null;
                    $items[] = [
                        'shop_id'     => $shop->id,
                        'day_of_week' => $day->value,
                        'open_time'   => $time ? $time[0] : null,
                        'close_time'  => $time ? $time[1] : null,
                        'created_at'  => $this->now,
                        'updated_at'  => $this->now,
                    ];
                }
            }
        });

        $this->insertData('shop_business_hours', $items);

        $this->finalize('ShopBusinessHourSeeder', [
            '営業時間数' => count($items),
        ]);
    }

    private static function presetWeekday(): array
    {
        return [
            null,
            ['10:00', '19:00'],
            ['10:00', '19:00'],
            ['10:00', '19:00'],
            ['10:00', '19:00'],
            ['10:00', '19:00'],
            null,
        ];
    }

    private static function presetWeekdayAlt(): array
    {
        return [
            ['11:00', '19:00'],   // 日
            ['11:00', '20:00'],   // 月〜金
            ['11:00', '20:00'],
            ['11:00', '20:00'],
            ['11:00', '20:00'],
            ['11:00', '20:00'],
            ['11:00', '19:00'],   // 土
        ];
    }

    private static function presetIrregular(): array
    {
        return array_fill(0, 7, ['10:00', '19:00']);
    }

    private static function presetAllNull(): array
    {
        return array_fill(0, 7, null);
    }

    private static function presets(): array
    {
        return [
            self::presetWeekday(),
            self::presetWeekdayAlt(),
            self::presetIrregular(),
        ];
    }
}
