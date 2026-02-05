<?php

namespace App\Services\Holiday;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class JapaneseHolidayService
{
    public function __invoke(int $year): array
    {
        $map = Cache::remember('japanese_holidays', config('holiday.cache.ttl_seconds', 86400), function () {
            $response = Http::timeout(5)->get(config('holiday.api.url'));
            if (!$response->successful()) {
                \Log::warning('Japanese holidays api failed.', [
                    'status' => $response->status()
                ]);
                return [];
            }
            return $response->json() ?? [];
        });

        return collect(array_keys($map))
            ->filter(fn(string $date) => str_starts_with($date, $year . '-'))
            ->sort()
            ->values()
            ->all();
    }
}
