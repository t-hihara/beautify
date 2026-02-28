<?php

namespace App\UseCases\Manager\Plan;

use App\Models\Plan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeletePlanUseCase
{
    public function __invoke(Plan $plan): bool
    {
        return DB::transaction(function () use ($plan) {
            $plan->load(['image', 'menus']);
            if (Storage::disk($plan->image->disk)->exists($plan->image->file_path)) {
                Storage::disk($plan->image->disk)->delete($plan->image->file_path);
            }
            $plan->image->delete();
            $plan->menus->each->delete();
            $plan->delete();

            return true;
        });
    }
}
