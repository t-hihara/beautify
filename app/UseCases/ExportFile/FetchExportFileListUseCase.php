<?php

namespace App\UseCases\ExportFile;

use App\Models\ExportFile;
use App\Utilities\RecursiveCovert;

class FetchExportFileListUseCase
{
    public function __invoke(array $filters, int $userId): array
    {
        $convert = RecursiveCovert::_convert($filters, 'snake');

        $files = ExportFile::byUserId($userId)
            ->bySubject($convert['subject'] ?? null)
            ->byDuration($convert['from_date'] ?? null, $convert['to_date'] ?? null)
            ->orderByDesc('created_at')
            ->paginate($convert['per_page'] ?? 10)
            ->through(fn($file) => [
                'id'           => $file->id,
                'subject'      => $file->subject,
                'filename'     => $file->filename,
                'status'       => $file->status->description(),
                'downloadedAt' => $file->downloaded_at,
                'createdAt'    => $file->created_at,
            ]);

        return [
            'filters'    => $filters,
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
}
