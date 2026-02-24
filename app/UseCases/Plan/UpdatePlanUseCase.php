<?php

namespace App\UseCases\Plan;

use App\Models\Plan;
use App\Utilities\RecursiveCovert;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdatePlanUseCase
{
    public function __invoke(array $payload, Plan $plan): Plan
    {
        $convert = RecursiveCovert::_convert($payload, 'snake');

        return DB::transaction(function () use ($convert, $plan) {
            $planData  = Arr::except($convert, ['image', 'menu_ids']);
            $imageData = $convert['image'] ?? null;
            $menuIds   = $convert['menu_ids'];

            $plan->fill($planData)->save();
            $plan->menus()->sync($menuIds);

            if ($imageData instanceof UploadedFile) {
                $disk = config('filesystems.default');
                $path = $imageData->store('plans/' . $plan->id, $disk);
                $data = [
                    'disk' => $disk,
                    'file_path' => $path,
                    'file_name' => $imageData->getClientOriginalName(),
                    'mime_type' => $imageData->getMimeType(),
                    'file_size' => $imageData->getSize(),
                ];

                if ($old = $plan->image) {
                    Storage::disk($old->disk)->delete($old->file_path);
                }
                $plan->image()->updateOrCreate([], $data);
            }

            return $plan;
        });
    }
}
