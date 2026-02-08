<?php

namespace Database\Seeders;

use App\Enum\ActiveFlagTypeEnum;
use Carbon\Carbon;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShopSeeder extends BaseSeeder
{
    private Generator $faker;
    private Carbon $now;

    public function __construct()
    {
        $this->faker = Factory::create('ja_JP');
        $this->now   = now();
    }

    public function run(): void
    {
        $this->initialize();

        $items = [];
        $descriptions = [
            '当店はお客様に最高のサービスを提供いたします。',
            'プロフェッショナルな技術で、お客様の美をサポートします。',
            'リラックスできる空間で、心も体もリフレッシュしていただけます。',
            'お客様一人ひとりに合わせたカスタマイズサービスをご提供します。',
        ];

        for ($i = 1; $i <= 200; $i++) {
            $items[] = [
                'name'          => $this->faker->company() . 'サロン',
                'email'         => $this->faker->unique->safeEmail(),
                'phone'         => $this->faker->phoneNumber(),
                'prefecture_id' => rand(1, 47),
                'zipcode'       => str_replace('-', '', $this->faker->postcode()),
                'address'       => $this->faker->city() . $this->faker->streetAddress(),
                'building'      => $this->faker->boolean(60) ? $this->faker->buildingNumber() . ' ' . $this->faker->secondaryAddress() : null,
                'description'   => $descriptions[array_rand($descriptions)],
                'active_flag'   => $this->faker->boolean(60) ? ActiveFlagTypeEnum::ACTIVE : ActiveFlagTypeEnum::INACTIVE,
            ];
        }


        $this->insertData('shops', $items);

        $this->finalize('ShopSeeder', [
            '店舗数' => count($items)
        ]);
    }
}
