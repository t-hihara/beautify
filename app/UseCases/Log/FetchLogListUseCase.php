<?php

namespace App\UseCases\Log;

use App\Models\ActivityLog;

class FetchLogListUseCase
{
    public function __invoke(): array
    {
        $logs = ActivityLog::with(['causer', 'subject'])
            ->paginate(20)
            ->through(fn($log) => [
                'id' => $log->id,
                'description' => $log->description,
                'event' => $log->event,
                'causer' => $log->causer?->name,
                'createdAt' => $log->created_at,
            ]);

        return [
            'logs' => $logs->items(),
            'links' => $logs->linkCollection(),
            'pagination' => [
                'currentPage' => $logs->currentPage(),
                'lastPage' => $logs->lastPage(),
                'prev' => $logs->previousPageUrl(),
                'next' => $logs->nextPageUrl(),
                'total' => $logs->total(),
            ],
        ];
    }
}
