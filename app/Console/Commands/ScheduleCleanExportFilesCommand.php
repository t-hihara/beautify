<?php

namespace App\Console\Commands;

use App\Enum\ExportFileStatusTypeEnum;
use App\Models\ExportFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class ScheduleCleanExportFilesCommand extends Command
{
    protected $signature = 'app:schedule-clean-export-files-command {--dry-run}';

    protected $description = '失敗した出力ファイルデータを削除する';

    public function handle(): void
    {
        activity()->disableLogging();
        \Log::info('Start Delete Export Files...' . now());

        try {
            DB::transaction(function () {
                $deleted = ExportFile::where('status', ExportFileStatusTypeEnum::FAILED)
                    ->delete();

                $deleted === 0
                    ? \Log::info('対象がありません。')
                    : \Log::info("削除: {$deleted}件");

                \Log::info('Success Delete Export Files');
            });
        } catch (Throwable $e) {
            report($e);
        }
    }
}
