<?php

namespace App\Services\Export;

use App\Enum\ExportFileStatusTypeEnum;
use App\Models\ExportFile;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Excel as ExcelType;

class ExportFileService
{
    public function getUnloadedFileCount(int $userId): int
    {
        return ExportFile::byUserId($userId)
            ->byStatus(ExportFileStatusTypeEnum::COMPLETED)
            ->byDownloaded(false)
            ->count();
    }

    public function createExportFile(int $userId, string $subject, array $filters, string $type): ExportFile
    {
        $datetime = Carbon::now()->format('Y-m-d H:i:s');
        $filename = sprintf('%s_%s_$s.$s', $subject, Str::random(20), $datetime, $type);
        $filepath = 'exports/' . $filename;

        return ExportFile::create([
            'user_id'   => $userId,
            'subject'   => $subject,
            'filename'  => $filename,
            'file_type' => $type,
            'file_path' => $filepath,
            'status'    => ExportFileStatusTypeEnum::PENDING,
            'filters'   => $filters,
        ]);
    }

    public function resolveExcelType(string $type): string
    {
        return match (strtolower($type)) {
            'xlsx'  => ExcelType::XLSX,
            'csv'   => ExcelType::CSV,
            default => ExcelType::CSV,
        };
    }
}
