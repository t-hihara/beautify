<?php

namespace App\Exports;

use App\Enum\ExportFileStatusTypeEnum;
use App\Models\ExportFile;
use App\Models\Menu;
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

class ExportMenu implements FromQuery, ShouldQueue, WithCustomCsvSettings, WithHeadings, WithMapping, WithEvents
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

    public function query()
    {
        return $this->queryWithFilters()
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function headings(): array
    {
        return [
            'ID',
            '店舗名',
            'メニュー名',
            'タイプ',
            '料金',
            '所要時間',
            '公開状態',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->shop->name,
            $row->name,
            $row->type->description(),
            $row->price,
            $row->duration,
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
        return Menu::with(['shop'])
            ->when($this->filters['name'] ?? null, fn(Builder $query, $name) => $query->where('name', 'like', "$name"))
            ->when($this->filters['shop_ids'] ?? null, fn(Builder $query, $shopIds) => $query->whereIn('shop_id', $shopIds))
            ->when($this->filters['types'] ?? null, fn(Builder $query, $types) => $query->whereIn('types', $types))
            ->when($this->filters['active_flat'] ?? null, fn(Builder $query, $flag) => $query->where('active_flag', $flag));
    }
}
