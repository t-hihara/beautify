<?php

namespace App\Exports;

use App\Enum\ExportFileStatusTypeEnum;
use App\Models\ExportFile;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;
use Throwable;

abstract class ExportBase
{
    public function __construct(
        private array $filters,
        private int $exportFileId,
        private string $disk = 's3'
    ) {}

    public function getCsvSettings(): array
    {
        return [
            'use_bom'         => true,
            'output_encoding' => 'UTF-8',
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function () {
                ExportFile::find($this->exportFileId)?->update([
                    'status' => ExportFileStatusTypeEnum::PROCESSING,
                ]);
            },
            AfterSheet::class => function () {
                $file = ExportFile::find($this->exportFileId);
                if (!$file) {
                    return;
                }
                $filesize = Storage::disk($this->disk)->exists($file->file_path)
                    ? Storage::disk($this->disk)->size($file->file_path)
                    : 0;

                $file->update([
                    'status'    => ExportFileStatusTypeEnum::COMPLETED,
                    'file_size' => $filesize,
                ]);
            },
        ];
    }

    public function failed(Throwable $e): void
    {
        ExportFile::find($this->exportFileId)?->update([
            'status'        => ExportFileStatusTypeEnum::FAILED,
            'error_message' => $e->getMessage(),
        ]);
    }
}
