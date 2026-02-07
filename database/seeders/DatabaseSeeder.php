<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $start = microtime(true);
        Schema::disableForeignKeyConstraints();

        try {
            $this->call([
                PrefectureSeeder::class,
                ShopSeeder::class,
                ShopImageSeeder::class,
                UserSeeder::class,
                RoleAndPermissionSeeder::class,
                CustomerSeeder::class,
                ExportFileSeeder::class,
            ]);
        } finally {
            Schema::enableForeignKeyConstraints();
            $end = microtime(true);
            $time = $end - $start;
            \Log::info("実行時間: {$time}秒");
        }
    }
}
