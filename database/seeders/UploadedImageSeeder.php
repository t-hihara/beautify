<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Shop;
use App\Models\ShopStaff;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UploadedImageSeeder extends BaseSeeder
{
    private Carbon $now;
    private const CHUNK_SIZE = 1000;

    public function __construct()
    {
        $this->now   = now();
    }

    public function run(): void
    {
        $this->initialize();

        $items       = [];
        $countByType = [
            'shop'       => 0,
            'shop_staff' => 0,
            'plan'       => 0
        ];

        Shop::chunkById(self::CHUNK_SIZE, function ($shops) use (&$items, &$countByType) {
            foreach ($shops as $shop) {
                $number = rand(1, 5);
                for ($i = 1; $i <= $number; $i++) {
                    $items[] = [
                        'disk'           => 's3',
                        'file_path'      => "https://picsum.photos/seed/shop-{$shop->id}-{$i}/800/600",
                        'file_name'      => "shop-{$shop->id}-{$i}.jpg",
                        'mime_type'      => 'jpg',
                        'file_size'      => 0,
                        'imageable_id'   => $shop->id,
                        'imageable_type' => Shop::class,
                        'created_at'     => $this->now,
                        'updated_at'     => $this->now,
                    ];
                }
                $countByType['shop'] += $number;
            }
        });

        ShopStaff::chunkById(self::CHUNK_SIZE, function ($staffs) use (&$items, &$countByType) {
            foreach ($staffs as $staff) {
                $items[] = [
                    'disk'           => 's3',
                    'file_path'      => "https://picsum.photos/seed/staff-{$staff->id}/800/600",
                    'file_name'      => "staff-{$staff->id}.jpg",
                    'mime_type'      => 'jpg',
                    'file_size'      => 0,
                    'imageable_id'   => $staff->id,
                    'imageable_type' => ShopStaff::class,
                    'created_at'     => $this->now,
                    'updated_at'     => $this->now,
                ];
                $countByType['shop_staff']++;
            }
        });

        Plan::chunkById(self::CHUNK_SIZE, function ($plans) use (&$items, &$countByType) {
            foreach ($plans as $plan) {
                $items[] = [
                    'disk'           => 's3',
                    'file_path'      => "https://picsum.photos/seed/plan-{$plan->id}/800/600",
                    'file_name'      => "plan-{$plan->id}.jpg",
                    'mime_type'      => 'jpg',
                    'file_size'      => 0,
                    'imageable_id'   => $plan->id,
                    'imageable_type' => Plan::class,
                    'created_at'     => $this->now,
                    'updated_at'     => $this->now,
                ];
                $countByType['plan']++;
            }
        });

        $this->insertData('uploaded_images', $items);

        $this->finalize('UploadedImageSeeder', [
            '店舗画像数'       => $countByType['shop'],
            '店舗スタッフ画像数' => $countByType['shop_staff'],
            'プラン画像数'      => $countByType['plan'],
            '合計'             => count($items)
        ]);
    }
}
