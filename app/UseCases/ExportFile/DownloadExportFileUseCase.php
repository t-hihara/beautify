<?php

namespace App\UseCases\ExportFile;

use App\Models\ExportFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DownloadExportFileUseCase
{
    public function __invoke(ExportFile $exportFile): StreamedResponse
    {
        if (!Storage::disk('s3')->exists($exportFile->file_path)) {
            throw new NotFoundHttpException('ファイルが見つかりません。');
        }

        DB::transaction(function () use ($exportFile) {
            $exportFile->update([
                'downloaded_at' => now(),
            ]);
        });

        return Storage::disk('s3')->download($exportFile->file_path, $exportFile->filename);
    }
}
