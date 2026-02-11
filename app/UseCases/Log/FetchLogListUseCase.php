<?php

namespace App\UseCases\Log;

use App\Models\ActivityLog;
use App\Utilities\RecursiveCovert;
use Illuminate\Support\Collection;

class FetchLogListUseCase
{
    public function __invoke(array $filters): array
    {
        $convert = RecursiveCovert::_convert($filters, 'snake');

        $logs = ActivityLog::with(['causer', 'subject'])
            ->byName($convert['name'] ?? null)
            ->byDuration($convert['from_date'] ?? null, $convert['to_date'] ?? null)
            ->paginate($convert['per_page'] ?? 10)
            ->through(fn($log) => [
                'id'          => $log->id,
                'description' => $log->description,
                'event'       => $log->event,
                'causer'      => $log->causer?->name,
                'createdAt'   => $log->created_at,
                'properties'  => $this->formatProperties($log->properties),
            ]);

        return [
            'filters'    => $filters,
            'logs'       => $logs->items(),
            'links'      => $logs->linkCollection(),
            'pagination' => [
                'currentPage' => $logs->currentPage(),
                'lastPage'    => $logs->lastPage(),
                'prev'        => $logs->previousPageUrl(),
                'next'        => $logs->nextPageUrl(),
                'total'       => $logs->total(),
            ],
        ];
    }

    private function formatProperties(Collection $properties): array
    {
        $changes = [];
        $old     = $properties->get('old') ?? [];
        $new     = $properties->get('attributes') ?? [];
        $keys    = array_keys($old + $new);

        foreach ($keys as $key) {
            $changes[] = [
                'attribute' => $key,
                'oldValue'  => array_key_exists($key, $old) ? $old[$key] : null,
                'newValue'  => array_key_exists($key, $new) ? $new[$key] : null,
            ];
        }

        return $changes;
    }
}
