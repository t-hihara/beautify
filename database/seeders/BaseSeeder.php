<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

abstract class BaseSeeder extends Seeder
{
    private float $startTime;
    private const CHUNK_SIZE = 1000;

    protected function initialize(): void
    {
        $this->startTime = microtime(true);
    }

    protected function insertData(string $table, array $data, int $batchSize = self::CHUNK_SIZE): void
    {
        if (empty($data)) return;

        $chunks = array_chunk($data, $batchSize);

        DB::transaction(function () use ($chunks, $table) {
            foreach ($chunks as $chunk) {
                DB::table($table)->insert($chunk);
            }
        });
    }

    protected function finalize(string $seederName, array $status = []): void
    {
        $end  = microtime(true);
        $time = $end - $this->startTime;

        \Log::info('==================================');
        \Log::info("{$seederName}");

        foreach ($status as $key => $value) {
            \Log::info("{$key}: {$value}");
        }

        \Log::info("実行時間: {$time}秒");
    }
}
