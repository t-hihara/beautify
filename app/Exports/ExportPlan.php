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

class ExportPlan extends ExportBase implements FromQuery, WithCustomCsvSettings, WithHeadings, WithMapping, WithEvents
{

    public function query(): Builder
    {
        return $this->queryWithFilters($this->filters)
            ->orderBy('shop_id')
            ->orderBy('sort_order');
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
            $plan->duration,
            $plan->regular_price,
            $plan->selling_price,
            $plan->condition_type?->description() ?? '----',
            $plan->active_flag->description(),
            $plan->valid_from ?? '----',
            $plan->valid_to ?? '----',
        ];
    }

    private function queryWithFilters(array $convert): Builder
    {
        return Plan::with(['shop', 'menus'])
            ->when($convert['name'] ?? null, fn(Builder $query, $name) => $query->where('name', 'like', "%{$name}%"))
            ->when($convert['active_flag'] ?? null, fn(Builder $query, $flag) => $query->where('active_flag', $flag))
            ->when($convert['shop_ids'] ?? null, fn(Builder $query, $shopIds) => $query->whereIn('shop_id', $shopIds))
            ->when($convert['types'] ?? null, fn(Builder $query, $types) => $query->whereHas('menus', fn(Builder $q) => $q->whereIn('type', $types)))
            ->when(
                ($convert['valid_from'] ?? null) && ($convert['valid_to'] ?? null),
                fn(Builder $query, $_) => $query->where('valid_from', '>=', $convert['valid_from'])->where('valid_to', '<=', $convert['valid_to'])
            )
            ->when(
                ($convert['valid_from'] ?? null) && !($convert['valid_to'] ?? null),
                fn(Builder $query, $_) => $query->where('valid_from', '>=', $convert['valid_from'])
            )
            ->when(
                !($convert['valid_from'] ?? null) && ($convert['valid_to'] ?? null),
                fn(Builder $query, $_) => $query->where('valid_to', '<=', $convert['valid_to'])
            );
    }
}
