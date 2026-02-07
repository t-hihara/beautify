<?php

namespace App\Jobs;

use App\Enum\ExportFileStatusTypeEnum;
use App\Exports\ExportShop;
use App\Models\ExportFile;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class ExportShopJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct(
        private array $filters,
        private int $exportFileId,
        private string $filepath,
        private string $type,
    ) {}

    public function handle(): void
    {
        try {
            ExportFile::find($this->exportFileId)->update([
                'status' => ExportFileStatusTypeEnum::PROCESSING,
            ]);

            Excel::store(new ExportShop($this->filters, $this->exportFileId), $this->filepath, 's3', $this->type);

            $filesize = Storage::disk('s3')->exists($this->filepath)
                ? Storage::disk('s3')->size($this->filepath)
                : 0;

            ExportFile::find($this->exportFileId)->update([
                'status'    => ExportFileStatusTypeEnum::COMPLETED,
                'file_size' => $filesize,
            ]);
        } catch (Throwable $e) {
            ExportFile::find($this->exportFileId)->update([
                'status'        => ExportFileStatusTypeEnum::FAILED,
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
