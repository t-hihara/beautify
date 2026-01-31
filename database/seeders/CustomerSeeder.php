<?php

namespace Database\Seeders;

use App\Enum\GenderTypeEnum;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends BaseSeeder
{
    private const CHUNK_SIZE = 1000;
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

        User::role('user', 'user')
            ->chunkById(self::CHUNK_SIZE, function ($users) use (&$items) {
                foreach ($users as $user) {
                    $items[] = [
                        'user_id'    => $user->id,
                        'name'       => $user->name,
                        'name_kana'  => $user->name_kana,
                        'email'      => $user->email,
                        'phone'      => '090' . str_pad((string) mt_rand(0, 99_999_999), 8, '0', STR_PAD_LEFT),
                        'dob'        => date('Y-m-d', mt_rand(strtotime('1970-01-01'), strtotime('2010-12-31'))),
                        'gender'     => mt_rand(0, 1) === 1 ? GenderTypeEnum::MALE : GenderTypeEnum::FEMALE,
                        'created_at' => $this->now,
                        'updated_at' => $this->now,
                    ];
                }
            });

        $this->insertData('customers', $items);

        $this->finalize('CustomerSeeder', [
            '顧客' => count($items) . '数'
        ]);
    }
}
