<?php

namespace App\UseCases\Plan;

use App\Models\Plan;
use App\Utilities\RecursiveCovert;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CreatePlanUseCase
{
    public function __construct(
        private Plan $plan,
    ) {}

    public function __invoke(array $payload): ?Plan
    {
        $convert = RecursiveCovert::_convert($payload, 'snake');

        return DB::transaction(function () use ($convert) {
            $planData  = Arr::except($covert, ['image', 'menu_ids']);
            $imageData = $convert['image'];
            $menuIds   = $convert['menu_ids'];

            $this->plan->fill($planData)->save();
            $this->plan->menus()->sync($menuIds);

            if ($imageData instanceof UploadedFile) {
                $disk = config('filesystems.default');
                $path = $imageData->store('plans/' . $this->plan->id, $disk);
                $data = [
                    'disk'      => $disk,
                    'file_path' => $path,
                    'file_name' => $imageData->getClientOriginalName(),
                    'mime_type' => $imageData->getMimeType(),
                    'file_size' => $imageData->getSize(),
                ];

                $this->plan->image()->create($data);
            }
            return $this->plan;
        });
    }
}
