<?php

namespace App\UseCases\Shop;

use App\Enum\ExportFileStatusTypeEnum;
use App\Models\ExportFile;
use App\Utilities\RecursiveCovert;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Excel as ExcelType;
use Maatwebsite\Excel\Facades\Excel;

class ExportShopUseCase
{
    public function __invoke(int $userId, array $filters, string $type)
    {
        $convert = RecursiveCovert::_convert($filters, 'snake');

        return DB::transaction(function () use ($userId, $convert, $type) {
            $datetime = Carbon::now()->format('Y-m-d_H:i:s');
            $filename = 'shop_' . Str::random(20) . '_' . $datetime . '.' . $type;
            $filepath = 'exports/' . $filename;

            $exportFile = ExportFile::create([
                'user_id'   => $userId,
                'subject'   => 'shop',
                'filename'  => $filename,
                'file_type' => $type,
                'file_path' => $filepath,
                'status'    => ExportFileStatusTypeEnum::PENDING,
                'filters'   => json_encode($convert),
            ]);
            $excelType = match (strtolower($type)) {
                'xlsx'  => ExcelType::XLSX,
                'csv'   => ExcelType::CSV,
                default => ExcelType::CSV,
            };
        });
    }
}
