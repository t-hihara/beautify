<?php

namespace App\UseCases\Log;

use App\Models\ActivityLog;
use App\Utilities\RecursiveCovert;

class FetchLogListUseCase
{
    public function __invoke(array $filters): array
    {
        $convert = RecursiveCovert::_convert($filters, 'snake');
        $logs = ActivityLog::with(['causer', 'subject'])
            ->byName($convert['name'] ?? null)
            ->byDuration($convert['from_date'] ?? null, $convert['to_date'] ?? null)
            ->paginate(20)
            ->through(fn($log) => [
                'id'          => $log->id,
                'description' => $log->description,
                'event'       => $log->event,
                'causer'      => $log->causer?->name,
                'createdAt'   => $log->created_at,
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
}
