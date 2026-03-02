<?php

namespace App\Exports;

use App\Enum\ExportFileStatusTypeEnum;
use App\Models\ExportFile;
use App\Models\Shop;
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

class ExportShop implements FromQuery, ShouldQueue, WithCustomCsvSettings, WithHeadings, WithMapping, WithEvents
{
    public function __construct(
        private array $filters,
        private int $exportFileId,
        private string $disk = 's3',
    ) {}

    public function query(): Builder
    {
        return $this->queryWithFilters()
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

    private function queryWithFilters(): Builder
    {
        return Shop::with(['area', 'businessHours', 'prefecture', 'station'])
            ->when($this->filters['name'] ?? null, fn(Builder $query, $name) => $query->where('name', 'like', "%$name%"))
            ->when($this->filters['email'] ?? null, fn(Builder $query, $email) => $query->where('email', 'like', "$email"))
            ->when($this->filters['phone'] ?? null, fn(Builder $query, $phone) => $query->where('phone', $phone))
            ->when($this->filters['prefecture_ids'] ?? null, fn(Builder $query, $prefectures) => $query->whereIn('prefecture_id', $prefectures))
            ->when($this->filters['active_flag'] ?? null, fn(Builder $query, $flag) => $query->where('active_flag', $flag));
    }
}
