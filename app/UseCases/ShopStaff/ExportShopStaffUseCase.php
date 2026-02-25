<?php

namespace App\UseCases\ShopStaff;

use App\Enum\ExportFileStatusTypeEnum;
use App\Exports\ExportShopStaff;
use App\Models\ExportFile;
use App\Services\Export\ExportFileService;
use App\Utilities\RecursiveCovert;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Excel as ExcelType;
use Maatwebsite\Excel\Facades\Excel;

class ExportShopStaffUseCase
{
    public function __construct(
        private readonly ExportFileService $exportFileService,
    ) {}

    public function __invoke(int $userId, array $filters, string $type, ?int $shopId = null): ExportFile
    {
        $convert = RecursiveCovert::_convert($filters, 'snake');
        if ($shopId) {
            $convert['shop_ids'] = [$shopId];
        }

        return DB::transaction(function () use ($userId, $convert, $type) {
            $datetime = Carbon::now()->format('Y-m-d H:i:s');
            $filename = 'shop-staff_' . Str::random(20) . '_' . $datetime . '.' . $type;
            $filepath = 'exports/' . $filename;

            $exportFile = $this->exportFileService->createExportFile($userId, 'shop-staff', $convert, $type);
            $exportType = $this->exportFileService->resolveExcelType($type);

            Excel::queue(new ExportShopStaff($convert, $exportFile->id), $filepath, 's3', $exportType);

            return $exportFile;
        });
    }
}
