<?php

namespace Database\Seeders;

use App\Enum\MenuTypeEnum;
use App\Models\Shop;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends BaseSeeder
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
        $menus = $this->getMenus();
        $types = MenuTypeEnum::cases();
        $requiredTypes = [
            MenuTypeEnum::CUT,
            MenuTypeEnum::COLOR,
            MenuTypeEnum::PERM,
        ];
        $optionalTypes = array_values(array_filter(
            $types,
            fn($type) => !in_array($type, $requiredTypes, true)
        ));

        Shop::chunkById(self::CHUNK_SIZE, function ($shops) use (
            &$items,
            $menus,
            $types,
            $requiredTypes,
            $optionalTypes
        ) {
            foreach ($shops as $shop) {
                $numOptional     = random_int(2, 7);
                $selectedOptions = $this->randomSelect($optionalTypes, $numOptional);
                $selectedTypes   = array_merge($requiredTypes, $selectedOptions);
                $sortOrder       = 1;

                foreach ($selectedTypes as $type) {
                    $typeValue = $type->value;
                    if (!isset($menus[$typeValue])) {
                        continue;
                    }

                    $available     = $menus[$typeValue];
                    $numMenus      = random_int(1, min(2, count($available)));
                    $selectedMenus = $this->randomSelect($available, $numMenus);

                    foreach ($selectedMenus as $menu) {
                        $items[] = [
                            'shop_id'     => $shop->id,
                            'type'        => $typeValue,
                            'name'        => $menu['name'],
                            'description' => $menu['description'],
                            'price'       => $menu['price'],
                            'duration'    => $menu['duration'],
                            'active_flag' => $shop->active_flag->value,
                            'sort_order'  => $sortOrder++,
                            'created_at'  => $this->now,
                            'updated_at'  => $this->now,
                        ];
                    }
                }
            }
        });

        $this->insertData('menus', $items);

        $this->finalize('MenuSeeder', [
            'メニュー数' => count($items),
        ]);
    }

    private function getMenus(): array
    {
        return [
            MenuTypeEnum::CUT->value => [
                ['name' => 'カット', 'price' => 4000, 'duration' => 45, 'description' => 'スタンダードなカットです。'],
                ['name' => 'ショートカット', 'price' => 4500, 'duration' => 50, 'description' => 'ショートスタイルのカット。'],
                ['name' => 'レディースカット', 'price' => 5000, 'duration' => 60, 'description' => 'レディース向けの丁寧なカット。'],
            ],
            MenuTypeEnum::COLOR->value => [
                ['name' => '全体カラー', 'price' => 8000, 'duration' => 90, 'description' => '全体のカラーリング。'],
                ['name' => 'ハイライト', 'price' => 10000, 'duration' => 120, 'description' => 'ハイライトカラー。'],
                ['name' => 'リタッチ', 'price' => 5500, 'duration' => 60, 'description' => '根元のリタッチ。'],
            ],
            MenuTypeEnum::PERM->value => [
                ['name' => 'デジタルパーマ', 'price' => 9000, 'duration' => 90, 'description' => 'くせを活かしたデジタルパーマ。'],
                ['name' => 'コールドパーマ', 'price' => 8000, 'duration' => 75, 'description' => 'ナチュラルな仕上がりのコールドパーマ。'],
                ['name' => 'ストレートパーマ', 'price' => 12000, 'duration' => 120, 'description' => '縮毛矯正・ストレートパーマ。'],
            ],
            MenuTypeEnum::TREATMENT->value => [
                ['name' => '髪質改善トリートメント', 'price' => 4000, 'duration' => 30, 'description' => '髪質改善のトリートメント。'],
                ['name' => 'クイックトリートメント', 'price' => 2000, 'duration' => 15, 'description' => '短時間のトリートメント。'],
            ],
            MenuTypeEnum::HEAD_SPA->value => [
                ['name' => 'ヘッドスパ（30分）', 'price' => 3500, 'duration' => 30, 'description' => '頭皮と髪のヘッドスパ。'],
                ['name' => 'スカルプエステ', 'price' => 5000, 'duration' => 45, 'description' => '頭皮ケアのスカルプエステ。'],
            ],
            MenuTypeEnum::STRAIGHTENING->value => [
                ['name' => '縮毛矯正', 'price' => 12000, 'duration' => 120, 'description' => '縮毛矯正です。'],
                ['name' => 'ストレート', 'price' => 10000, 'duration' => 90, 'description' => 'ストレート矯正。'],
            ],
            MenuTypeEnum::HAIR_SET->value => [
                ['name' => 'ヘアセット', 'price' => 3500, 'duration' => 45, 'description' => '結婚式・パーティ用ヘアセット。'],
                ['name' => '編み込み', 'price' => 4500, 'duration' => 60, 'description' => '編み込みアレンジ。'],
            ],
            MenuTypeEnum::BANG_CUT->value => [
                ['name' => '前髪カット', 'price' => 1000, 'duration' => 15, 'description' => '前髪のみのカット。'],
            ],
            MenuTypeEnum::RETOUCH_COLOR->value => [
                ['name' => 'リタッチカラー', 'price' => 5000, 'duration' => 60, 'description' => '根元リタッチカラー。'],
            ],
            MenuTypeEnum::EYEBROW_CUT->value => [
                ['name' => '眉カット', 'price' => 500, 'duration' => 10, 'description' => '眉のカット・整え。'],
            ],
            MenuTypeEnum::SHAVING->value => [
                ['name' => 'シェービング', 'price' => 2000, 'duration' => 20, 'description' => '首周りシェービング。'],
                ['name' => '顔剃り', 'price' => 1500, 'duration' => 15, 'description' => '顔剃り。'],
            ],
            MenuTypeEnum::MENS_CUT->value => [
                ['name' => 'メンズカット', 'price' => 3500, 'duration' => 30, 'description' => 'メンズスタンダードカット。'],
                ['name' => 'ツーブロック', 'price' => 4500, 'duration' => 45, 'description' => 'ツーブロックカット。'],
            ],
            MenuTypeEnum::KITSUKE->value => [
                ['name' => '着付け', 'price' => 5000, 'duration' => 60, 'description' => '着物の着付け。'],
                ['name' => '浴衣ヘアセット', 'price' => 3000, 'duration' => 30, 'description' => '浴衣用ヘアセット。'],
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
