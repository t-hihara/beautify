<?php

namespace Database\Seeders;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\MenuTypeEnum;
use App\Models\Shop;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends BaseSeeder
{
    private Carbon $now;

    private const CHUNK_SIZE           = 1000;
    private const MIN_TYPES_PER_SHOP   = 5;
    private const MAX_TYPES_PER_SHOP   = 10;
    private const MIN_MENU_PER_TYPE    = 1;
    private const MAX_MENU_PER_TYPE    = 2;
    private const REQUIRED_TYPE_VALUES = [
        MenuTypeEnum::CUT->value,
        MenuTypeEnum::COLOR->value,
        MenuTypeEnum::PERM->value,
    ];

    public function __construct()
    {
        $this->now = now();
    }

    public function run(): void
    {
        $this->initialize();

        $items      = [];
        $menus      = $this->getMenus();
        $types      = MenuTypeEnum::cases();
        $typeValues = array_map(fn(MenuTypeEnum $type) => $type->value, $types);

        $optionalTypeValues = array_values(array_diff($typeValues, self::REQUIRED_TYPE_VALUES));

        Shop::chunkById(self::CHUNK_SIZE, function ($shops) use (&$items, $optionalTypeValues, $menus) {
            foreach ($shops as $shop) {
                $numberOfTypes    = random_int(self::MIN_TYPES_PER_SHOP, self::MAX_TYPES_PER_SHOP);
                $optionalCount    = $numberOfTypes - count(self::REQUIRED_TYPE_VALUES);
                $selectedOptional = array_slice($optionalTypeValues, 0, max(0, min($optionalCount, count($optionalTypeValues))));
                $selectedTypes    = array_merge(self::REQUIRED_TYPE_VALUES, $selectedOptional);
                $sortOrder        = 1;

                foreach ($selectedTypes as $typeValue) {
                    if (!isset($menus[$typeValue])) continue;
                    $menusInType = $menus[$typeValue];

                    $numberOfMenus = random_int(self::MIN_MENU_PER_TYPE, self::MAX_MENU_PER_TYPE);
                    $selectedMenus = array_slice($menusInType, 0, min($numberOfMenus, count($menusInType)));

                    foreach ($selectedMenus as $menu) {
                        $items[] = [
                            'shop_id'     => $shop->id,
                            'type'        => $typeValue,
                            'name'        => $menu['name'],
                            'description' => $menu['description'],
                            'price'       => $menu['price'],
                            'duration'    => $menu['duration'],
                            'active_flag' => ActiveFlagTypeEnum::ACTIVE->value,
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
}
