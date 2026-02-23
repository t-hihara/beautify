<?php

namespace App\UseCases\Plan;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\PlanConditionTypeEnum;
use App\Models\Plan;
use Illuminate\Support\Facades\Storage;

class PreparePlanEditFormUseCase
{
    public function __invoke(Plan $plan): array
    {
        $plan->load(['image', 'menus']);
        return [
            'plan' => [
                'id'            => $plan->id,
                'name'          => $plan->name,
                'description'   => $plan->description,
                'totalDuration' => $plan->total_duration,
                'regularPrice'  => $plan->regular_price,
                'sellingPrice'  => $plan->selling_price,
                'conditionType' => $plan->condition_type?->value,
                'activeFlag'    => $plan->active_flag->value,
                'sortOrder'     => $plan->sort_order,
                'validFrom'     => $plan->valid_from,
                'validTo'       => $plan->valid_to,
                'image'         => $plan->image ? [
                    'id'       => $plan->image->id,
                    'fileName' => $plan->image->file_name,
                    'filePath' => str_starts_with($plan->image->file_path, 'http')
                        ? $plan->image->file_path
                        : Storage::disk($plan->image->disk)->temporaryUrl($plan->image->file_path, now()->addMinutes(60)),
                ] : null,
            ],
            'activeFlags'    => ActiveFlagTypeEnum::options(),
            'conditionTypes' => PlanConditionTypeEnum::options(),
        ];
    }
}
