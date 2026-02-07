<?php

namespace App\UseCases\ExportFile;

use App\Models\ExportFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeleteExportFileUseCase
{
    public function __invoke(ExportFile $exportFile): bool
    {
        return DB::transaction(function () use ($exportFile) {
            if (Storage::disk('s3')->exists($exportFile->file_path)) {
                Storage::disk('s3')->delete('exports/' . $exportFile->filename);
            }
            $exportFile->delete();

            return true;
        });
    }
}
