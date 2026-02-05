<?php

namespace Database\Seeders;

use App\Models\Shop;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShopImageSeeder extends BaseSeeder
{
    private const CHUNK_SIZE = 1000;
    private Carbon $now;

    public function __construct()
    {
        $this->now   = now();
    }

    public function run(): void
    {
        $this->initialize();

        $items = [];

        Shop::chunkById(self::CHUNK_SIZE, function ($shops) use (&$items) {
            foreach ($shops as $shop) {
                for ($i = 1; $i <= rand(1, 5); $i++) {
                    $items[] = [
                        'shop_id' => $shop->id,
                        'file_path' => "https://picsum.photos/seed/shop-{$shop->id}-{$i}/800/600",
                        'filename' => "shop-{$shop->id}-{$i}.jpg",
                        'mime_type' => 'jpg',
                        'file_size' => 0,
                        'created_at' => $this->now,
                        'updated_at' => $this->now,
                    ];
                }
            }
        });

        $this->insertData('shop_images', $items);

        $this->finalize('ShopImageSeeder', [
            '店舗画像' => count($items) . '数'
        ]);
    }
}
