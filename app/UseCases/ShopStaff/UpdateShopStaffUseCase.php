<?php

namespace App\UseCases\ShopStaff;

use App\Models\ShopStaff;
use App\Utilities\RecursiveCovert;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateShopStaffUseCase
{
    public function __invoke(array $payload, ShopStaff $staff): ShopStaff
    {
        return DB::transaction(function () use ($payload, $staff) {
            $convert = RecursiveCovert::_convert($payload, 'snake');
            $staff->fill(Arr::except($convert, 'image'))->save();

            $image = $convert['image'] ?? null;
            if ($image instanceof UploadedFile) {
                $disk = config('filesystems.default');
                $path = $image->store('shop_staffs/' . $staff->id, $disk);
                $data = [
                    'disk'      => $disk,
                    'file_path' => $path,
                    'file_name' => $image->getClientOriginalName(),
                    'mime_type' => $image->getMimeType(),
                    'file_size' => $image->getSize(),
                ];

                if ($old = $staff->image) {
                    Storage::disk($old->disk)->delete($old->file_path);
                }
                $staff->image()->updateOrCreate([], $data);
            }
            return $staff;
        });
    }
}
