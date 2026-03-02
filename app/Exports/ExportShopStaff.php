<?php

namespace App\Exports;

use App\Enum\ExportFileStatusTypeEnum;
use App\Models\ExportFile;
use App\Models\ShopStaff;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;
use Throwable;

class ExportShopStaff implements FromQuery, ShouldQueue, WithCustomCsvSettings, WithHeadings, WithMapping, WithEvents
{
    public function __construct(
        private array $filters,
        private int $exportFileId,
        private string $disk = 's3',
    ) {}

    public function getCsvSettings(): array
    {
        return [
            'use_bom'         => true,
            'output_encoding' => 'UTF-8',
        ];
    }

    public function query(): Builder
    {
        return $this->queryWithFilters()
            ->orderBy('id');
    }

    public function headings(): array
    {
        return [
            'ID',
            'スタッフ名',
            '店舗名',
            'メールアドレス',
            'ポジション',
            '経歴年数',
            '有効状態',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->name,
            $row->shop->name,
            $row->email,
            $row->position->description(),
            $row->experience_years . '年',
            $row->active_flag->description(),
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function () {
                ExportFile::find($this->exportFileId)?->update([
                    'status' => ExportFileStatusTypeEnum::PROCESSING,
                ]);
            },
            AfterSheet::class => function () {
                $file = ExportFile::find($this->exportFileId);
                if (!$file) return;

                $fileSize = Storage::disk($this->disk)->exists($file->file_path)
                    ? Storage::disk($this->disk)->size($file->file_path)
                    : 0;

                $file->update([
                    'status'    => ExportFileStatusTypeEnum::COMPLETED,
                    'file_size' => $fileSize,
                ]);
            }
        ];
    }

    public function failed(Throwable $e): void
    {
        ExportFile::find($this->exportFileId)?->update([
            'status'        => ExportFileStatusTypeEnum::FAILED,
            'error_message' => $e->getMessage(),
        ]);
    }

    private function queryWithFilters(): Builder
    {
        return ShopStaff::with(['image', 'shop'])
            ->when($this->filters['name'] ?? null, fn(Builder $query, $name) => $query->where('name', 'like', "%$name%"))
            ->when($this->filters['shop_ids'] ?? null, fn(Builder $query, $shopIds) => $query->whereIn('shop_id', $shopIds))
            ->when($this->filters['active_flag'] ?? null, fn(Builder $query, $flag) => $query->where('active_flag', $flag))
            ->when($this->filters['positions'] ?? null, fn(Builder $query, $positions) => $query->whereIn('position', $positions));
    }
}
