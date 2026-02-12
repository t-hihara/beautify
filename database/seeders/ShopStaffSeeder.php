<?php

namespace Database\Seeders;

use App\Enum\ShopStaffPositionTypeEnum;
use App\Models\Shop;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ShopStaffSeeder extends BaseSeeder
{
    private Generator $faker;
    private Carbon $now;
    private const CHUNK_SIZE = 1000;

    private const OWNER_POSITIONS = [
        ShopStaffPositionTypeEnum::MANAGER,
        ShopStaffPositionTypeEnum::SALON_MANAGER,
    ];
    private const STAFF_POSITIONS = [
        ShopStaffPositionTypeEnum::TOP_STYLIST,
        ShopStaffPositionTypeEnum::STYLIST,
        ShopStaffPositionTypeEnum::JUNIOR_STYLIST,
        ShopStaffPositionTypeEnum::ASSISTANT,
    ];
    private const STYLIST_OR_ABOVE = [
        ShopStaffPositionTypeEnum::TOP_STYLIST,
        ShopStaffPositionTypeEnum::STYLIST,
    ];
    private const MIN_STYLISTS_PER_SHOP = 2;

    public function __construct()
    {
        $this->faker = Factory::create('ja_JP');
        $this->now   = now();
    }

    public function run(): void
    {
        $this->initialize();

        $items      = [];
        $stylistCount = [];

        User::role(['staff_owner', 'staff'], 'shop')
            ->chunkById(self::CHUNK_SIZE, function ($users) use (&$items, &$stylistCount) {
                $shopIdsInChunk = $users->map(fn($user) => $this->parseShopIdFromEmail($user->email))
                    ->unique()
                    ->filter()
                    ->values()
                    ->all();
                $existingShopIds = Shop::whereIn('id', $shopIdsInChunk)->pluck('id')->flip()->all();

                foreach ($users as $user) {
                    $shopId = $this->parseShopIdFromEmail($user->email);
                    if (!isset($existingShopIds[$shopId])) continue;

                    $isOwner = str_contains($user->email, 'staff_owner');
                    if ($isOwner) {
                        $position = Arr::random(self::OWNER_POSITIONS)->value;
                    } else {
                        $count = $stylistCount[$shopId] ?? 0;
                        if ($count < self::MIN_STYLISTS_PER_SHOP) {
                            $position = Arr::random(self::STYLIST_OR_ABOVE)->value;
                            $stylistCount[$shopId] = $count + 1;
                        } else {
                            $position = Arr::random(self::STAFF_POSITIONS)->value;
                        }
                    }

                    $items[] = [
                        'shop_id'          => $shopId,
                        'user_id'          => $user->id,
                        'name'             => $user->name,
                        'email'            => $user->email,
                        'position'         => $position,
                        'description'      => $this->faker->realText(80),
                        'experience_years' => rand(1, 20),
                        'active_flag'      => $user->active_flag,
                        'created_at'       => $this->now,
                        'updated_at'       => $this->now,
                    ];
                }
            });

        $this->insertData('shop_staff', $items);

        $this->finalize('ShopStaffSeeder', [
            '店舗スタッフ数' => count($items)
        ]);
    }

    private function parseShopIdFromEmail(string $email): ?int
    {
        return preg_match('/staff(_?owner)?(\d+)/', $email, $m) ? (int) $m[2] : null;
    }
}
