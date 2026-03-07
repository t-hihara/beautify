<?php

namespace App\UseCases\Operator\Menu;

use App\Enum\ExportFileStatusTypeEnum;
use App\Exports\ExportMenu;
use App\Models\ExportFile;
use App\Services\Export\ExportFileService;
use App\Utilities\RecursiveCovert;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Excel as ExcelType;
use Maatwebsite\Excel\Facades\Excel;

class ExportMenuUseCase
{
    public function __construct(
        private readonly ExportFileService $exportFileService,
    ) {}

    public function __invoke(int $userId, array $filters, string $type, ?int $shopId = null,): ExportFile
    {
        $convert = RecursiveCovert::_convert($filters, 'snake');
        if ($shopId) {
            $convert['shop_ids'] = [$shopId];
        }

        return DB::transaction(function () use ($userId, $convert, $shopId, $type) {
            $datetime = Carbon::now()->format('Y-m-d H:i:s');
            $filename = 'menu_' . Str::random(20) . '_' . $datetime . '.' . $type;
            $filepath = 'exports/' . $filename;

            $exportFile = $this->exportFileService->createExportFile($userId, 'menu', $convert, $type);
            $exportType = $this->exportFileService->resolveExcelType($type);

            Excel::queue(new ExportMenu($convert, $exportFile->id), $filepath, 's3', $exportType);

            return $exportFile;
        });
    }
}
