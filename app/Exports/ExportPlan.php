<?php

namespace App\Exports;

use App\Enum\ExportFileStatusTypeEnum;
use App\Models\ExportFile;
use App\Models\Plan;
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

class ExportPlan implements FromQuery, WithCustomCsvSettings, WithHeadings, WithMapping, WithEvents
{
    public function __construct(
        private array $filters,
        private int $exportFileId,
        private string $disk = 's3'
    ) {}

    public function query(): Builder
    {
        return Plan::with(['shop', 'menus'])
            ->byName($this->filters['name'] ?? null)
            ->byActiveFlag($this->filters['active_flag'] ?? null)
            ->byMenuTypes($this->filters['types'] ?? null)
            ->byValidDuration($this->filters['valid_from'] ?? null, $this->filters['valid_to'] ?? null)
            ->orderBy('shop_id')
            ->orderBy('sort_order');
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
            'プラン名',
            'メニュー名',
            '総時間',
            '定価価格',
            '販売価格',
            '適用条件種別',
            '公開状態',
            '期間限定（開始）',
            '期間限定（終了）',
        ];
    }

    public function map($plan): array
    {
        return [
            $plan->id,
            $plan->shop->name,
            $plan->name,
            $plan->menus->pluck('name')->implode('、') ?: '----',
            $plan->total_duration,
            $plan->regular_price,
            $plan->selling_price,
            $plan->condition_type?->description() ?? '----',
            $plan->active_flag->description(),
            $plan->valid_from ?? '----',
            $plan->valid_to ?? '----',
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
                if (!$file) {
                    return;
                }
                $filesize = Storage::disk($this->disk)->exists($file->file_path)
                    ? Storage::disk($this->disk)->size($file->file_path)
                    : 0;

                $file->update([
                    'status'    => ExportFileStatusTypeEnum::COMPLETED,
                    'file_size' => $filesize,
                ]);
            },
        ];
    }

    public function failed(Throwable $e): void
    {
        ExportFile::find($this->exportFileId)?->update([
            'status'        => ExportFileStatusTypeEnum::FAILED,
            'error_message' => $e->getMessage(),
        ]);
    }
}
