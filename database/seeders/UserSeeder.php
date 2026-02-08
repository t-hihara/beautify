<?php

namespace Database\Seeders;

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
                'last_name'       => "amin_{$i}",
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

    public function createUsers(array &$items): void
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
