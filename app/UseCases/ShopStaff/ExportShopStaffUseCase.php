<?php

namespace App\UseCases\ShopStaff;

use App\Enum\ExportFileStatusTypeEnum;
use App\Exports\ExportShopStaff;
use App\Models\ExportFile;
use App\Utilities\RecursiveCovert;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Excel as ExcelType;
use Maatwebsite\Excel\Facades\Excel;

class ExportShopStaffUseCase
{
    public function __invoke(int $userId, array $filters, ?int $shopId = null, string $type): PendingDispatch
    {
        $convert = RecursiveCovert::_convert($filters, 'snake');
        $shopId ? $convert['shop_ids'] = [$shopId] : null;

        return DB::transaction(function () use ($userId, $convert, $type) {
            $datetime = Carbon::now()->format('Y-m-d H:i:s');
            $filename = 'shop-staff_' . Str::random(20) . '_' . $datetime . '.' . $type;
            $filepath = 'exports/' . $filename;

            $exportFile = ExportFile::create([
                'user_id'   => $userId,
                'subject'   => 'shopStaff',
                'filename'  => $filename,
                'file_type' => $type,
                'file_path' => $filepath,
                'status'    => ExportFileStatusTypeEnum::PENDING,
                'filters'   => $convert,
            ]);

            $exportType = match (strtolower($type)) {
                'xlsx'  => ExcelType::XLSX,
                'csv'   => ExcelType::CSV,
                default => ExcelType::CSV,
            };

            Excel::queue(new ExportShopStaff($convert, $exportFile->id), $filepath, 's3', $excelType);
        });
    }
}
