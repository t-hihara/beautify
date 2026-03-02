<?php

namespace Database\Seeders;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\DayOfWeekTypeEnum;
use App\Models\Shop;
use App\Services\Holiday\JapaneseHolidayService;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShopHolidaySeeder extends BaseSeeder
{
    private Carbon $now;
    private const CHUNK_SIZE = 1000;

    public function __construct(
        private readonly JapaneseHolidayService $holidays
    ) {
        $this->now = now();
    }

    public function run(): void
    {
        $this->initialize();

        $items = [];
        $holidays = ($this->holidays)((int)$this->now->format('Y'));

        Shop::with(['businessHours'])
            ->where('active_flag', ActiveFlagTypeEnum::ACTIVE)
            ->chunkById(self::CHUNK_SIZE, function ($shops) use (&$items, $holidays) {
                foreach ($shops as $shop) {
                    $hoursByDay = $shop->businessHours->keyBy('day_of_week');
                    foreach ($holidays as $holiday) {
                        $date      = Carbon::parse($holiday);
                        $dayOfWeek = DayOfWeekTypeEnum::cases()[$date->dayOfWeek]->value;
                        $hour      = $hoursByDay->get($dayOfWeek);

                        if ($hour !== null && $hour->open_time !== null) {
                            $items[] = [
                                'shop_id'    => $shop->id,
                                'close_date' => $holiday,
                                'created_at' => $this->now,
                                'updated_at' => $this->now,
                            ];
                        }
                    }
                }
            });

        $this->insertData('shop_holidays', $items);

        $this->finalize('ShopHolidaySeeder', [
            '祝日数' => count($items),
        ]);
    }
}
