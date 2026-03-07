<?php

namespace App\UseCases\Operator\ExportFile;

use App\Models\ExportFile;
use App\Utilities\RecursiveCovert;
use Illuminate\Database\Eloquent\Builder;

class FetchExportFileListUseCase
{
    public function __invoke(array $filters, int $userId): array
    {
        $convert = RecursiveCovert::_convert($filters, 'snake');

        $files = $this->queryWithFilters($convert, $userId)
            ->orderByDesc('created_at')
            ->paginate($convert['per_page'] ?? 10)
            ->withQueryString()
            ->through(fn($file) => [
                'id'           => $file->id,
                'subject'      => $file->subject,
                'filename'     => $file->filename,
                'status'       => $file->status->description(),
                'downloadedAt' => $file->downloaded_at,
                'createdAt'    => $file->created_at,
            ]);

        return [
            'filters'     => array_merge($filters, [
                'perPage' => (int) ($filters['perPage'] ?? 10),
            ]),
            'files'      => $files->items(),
            'links'      => $files->linkCollection(),
            'pagination' => [
                'currentPage' => $files->currentPage(),
                'lastPage'    => $files->lastPage(),
                'prev'        => $files->previousPageUrl(),
                'next'        => $files->nextPageUrl(),
                'total'       => $files->total(),
            ],
        ];
    }

    private function queryWithFilters(array $convert, int $userId): Builder
    {
        return ExportFile::query()
            ->where('user_id', $userId)
            ->when($convert['subject'] ?? null, fn(Builder $q, $subject) => $q->where('subject', 'like', "%$subject%"))
            ->when(
                ($convert['from_date'] ?? null) && ($convert['to_date'] ?? null),
                fn(Builder $q, $_) => $q->whereBetween('created_at', [$convert['from_date'], $convert['to_date']])
            )
            ->when(
                ($convert['from_date'] ?? null) && !($convert['to_date'] ?? null),
                fn(Builder $q, $_) => $q->where('created_at', '>=', $convert['from_date'])
            )
            ->when(
                !($convert['from_date'] ?? null) && ($convert['to_date'] ?? null),
                fn(Builder $q, $_) => $q->where('created_at', '<=', $convert['to_date'])
            );
    }
}
