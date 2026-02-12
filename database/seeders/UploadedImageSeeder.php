<?php

namespace Database\Seeders;

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

        $items = [];

        Shop::chunkById(self::CHUNK_SIZE, function ($shops) use (&$items) {
            foreach ($shops as $shop) {
                for ($i = 1; $i <= rand(1, 5); $i++) {
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
            }
        });

        ShopStaff::chunkById(self::CHUNK_SIZE, function ($staffs) use (&$items) {
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
            }
        });

        $this->insertData('uploaded_images', $items);

        $this->finalize('UploadedImageSeeder', [
            '店舗画像数' => count($items)
        ]);
    }
}
