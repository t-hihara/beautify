<?php

namespace Database\Seeders;

use App\Models\Shop;
use Carbon\Carbon;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends BaseSeeder
{
    private static ?string $password = null;
    private Generator $faker;
    private Carbon $now;
    private const CHUNK_SIZE = 1000;

    public function __construct()
    {
        $this->faker = Factory::create('ja_JP');
        $this->now   = now();
    }

    public function run(): void
    {
        $this->initialize();

        $items = [];

        $this->createAdmin($items);
        $this->createShopStaffs($items);
        $this->createUsers($items);

        $this->insertData('users', $items);

        $this->finalize('UserSeeder', [
            'ユーザー数' => count($items)
        ]);
    }

    private static function getPassword(): string
    {
        return self::$password ??= Hash::make("test");
    }

    private function createAdmin(array &$items): void
    {
        for ($i = 1; $i <= 100; $i++) {
            $items[] = [
                'last_name'       => "admin_{$i}",
                'first_name'      => "test",
                'last_name_kana'  => null,
                'first_name_kana' => null,
                'email'           => "admin_{$i}@test.com",
                'password'        => self::getPassword(),
                'created_at'      => $this->now,
                'updated_at'      => $this->now,
            ];
        }
    }

    private function createShopStaffs(array &$items): void
    {
        Shop::chunkById(self::CHUNK_SIZE, function ($shops) use (&$items) {
            foreach ($shops as $shop) {
                for ($i = 1; $i <= rand(2, 5); $i++) {
                    $items[] = [
                        'last_name'       => "staff_owner{$shop->id}_{$i}",
                        'first_name'      => "test",
                        'last_name_kana'  => null,
                        'first_name_kana' => null,
                        'email'           => "staff_owner{$shop->id}_{$i}@test.com",
                        'password'        => self::getPassword(),
                        'created_at'      => $this->now,
                        'updated_at'      => $this->now,
                    ];
                }

                for ($i = 1; $i <= rand(5, 8); $i++) {
                    $items[] = [
                        'last_name'       => "staff{$shop->id}_{$i}",
                        'first_name'      => "test",
                        'last_name_kana'  => null,
                        'first_name_kana' => null,
                        'email'           => "staff{$shop->id}_{$i}@test.com",
                        'password'        => self::getPassword(),
                        'created_at'      => $this->now,
                        'updated_at'      => $this->now,
                    ];
                }
            }
        });
    }

    private function createUsers(array &$items): void
    {
        for ($i = 1; $i <= 5000; $i++) {
            $items[] = [
                'last_name'       => $this->faker->lastName(),
                'first_name'      => $this->faker->firstName(),
                'last_name_kana'  => $this->faker->lastKanaName(),
                'first_name_kana' => $this->faker->firstKanaName(),
                'email'           => "user_{$i}@test.com",
                'password'        => self::getPassword(),
                'created_at'      => $this->now,
                'updated_at'      => $this->now,
            ];
        }
    }
}
