<?php

namespace App\Exports;

use App\Enum\ExportFileStatusTypeEnum;
use App\Models\ExportFile;
use App\Models\Shop;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\BeforeExport;

class ExportShop implements FromQuery, ShouldQueue, WithCustomCsvSettings, WithHeadings, WithMapping
{
    public function __construct(
        private array $filters,
        private int $exportFileId,
    ) {}

    public function query(): Builder
    {
        return Shop::with(['prefecture'])
            ->byName($this->filters['name'])
            ->byEmail($this->filters['email'])
            ->byPhone($this->filters['phone'])
            ->byPrefectures($this->filters['prefecture_ids'])
            ->byActiveFlag($this->filters['active_flag'])
            ->orderBy('id');
    }

    public function getCsvSettings(): array
    {
        return [
            'use_bom'         => true,
            'output_encoding' => 'UTF-8',
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            '店舗名',
            'メールアドレス',
            '電話番号',
            '運営状態',
            '郵便番号',
            '都道府県',
            '住所',
            '建物・番地名',
        ];
    }

    public function map($shop): array
    {
        return [
            $shop->id,
            $shop->name,
            $shop->email,
            $shop->phone,
            $shop->active_flag->description(),
            $shop->zipcode,
            $shop->prefecture->name,
            $shop->address,
            $shop->building,
        ];
    }
}
