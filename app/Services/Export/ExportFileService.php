<?php

namespace App\Services\Export;

use App\Enum\ExportFileStatusTypeEnum;
use App\Models\ExportFile;

class ExportFileService
{
    public function getUnloadedFileCount(int $userId): int
    {
        return ExportFile::byUserId($userId)
            ->byStatus(ExportFileStatusTypeEnum::COMPLETED)
            ->byDownloaded(false)
            ->count();
    }
}
