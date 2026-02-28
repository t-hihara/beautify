<?php

namespace App\UseCases\Manager\Shop;

use App\Enum\ExportFileStatusTypeEnum;
use App\Exports\ExportShop;
use App\Models\ExportFile;
use App\Services\Export\ExportFileService;
use App\Utilities\RecursiveCovert;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Excel as ExcelType;
use Maatwebsite\Excel\Facades\Excel;

class ExportShopUseCase
{
    public function __construct(
        private readonly ExportFileService $exportFileService,
    ) {}

    public function __invoke(int $userId, array $filters, string $type): ExportFile
    {
        $convert = RecursiveCovert::_convert($filters, 'snake');

        return DB::transaction(function () use ($userId, $convert, $type) {
            $datetime = Carbon::now()->format('Y-m-d_H:i:s');
            $filename = 'shop_' . Str::random(20) . '_' . $datetime . '.' . $type;
            $filepath = 'exports/' . $filename;

            $exportFile = $this->exportFileService->createExportFile($userId, 'shop', $convert, $type);
            $exportType = $this->exportFileService->resolveExcelType($type);

            Excel::queue(new ExportShop($convert, $exportFile->id), $filepath, 's3', $excelType);

            return $exportFile;
        });
    }
}
