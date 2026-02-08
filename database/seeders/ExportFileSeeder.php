<?php

namespace Database\Seeders;

use App\Enum\ExportFileStatusTypeEnum;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ExportFileSeeder extends BaseSeeder
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

        User::whereDoesntHave('roles', function ($q) {
            $q->where('name', 'user')->where('guard_name', 'user');
        })
            ->chunkById(self::CHUNK_SIZE, function ($users) use (&$items) {
                foreach ($users as $user) {
                    for ($i = 1; $i <= rand(5, 20); $i++) {
                        $items[] = [
                            'user_id'   => $user->id,
                            'subject'   => 'shop',
                            'file_type' => 'csv',
                            'file_path' => Str::random(20),
                            'filename'  => Str::random(20),
                            'status'    => $this->faker->boolean(60) ? ExportFileStatusTypeEnum::COMPLETED : ExportFileStatusTypeEnum::FAILED,
                            'filters'   => json_encode([]),
                        ];
                    }
                }
            });

        $this->insertData('export_files', $items);

        $this->finalize('ExportFileSeeder', [
            '出力ファイル数' => count($items)
        ]);
    }
}
