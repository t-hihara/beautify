<?php

namespace App\UseCases\ShopStaff;

use App\Models\ShopStaff;
use App\Models\User;
use App\Utilities\RecursiveCovert;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class CreateShopStaffUseCase
{
    public function __construct(
        private User $user,
        private ShopStaff $staff,
    ) {}

    public function __invoke(array $payload): ?ShopStaff
    {
        return DB::transaction(function () use ($payload) {
            $convert   = RecursiveCovert::_convert($payload, 'snake');
            $userData  = Arr::except($convert, ['position', 'experience_years', 'image', 'description']);
            $staffData = Arr::except($convert, ['password', 'image']);

            $this->user->fill($userData)->save();
            $this->user->assignRole(Role::findByName('staff', 'shop'));
            $this->user->shopStaff()->create($staffData);

            $image = $convert['image'] ?? null;
            if ($image instanceof UploadedFile) {
                $disk = config('filesystems.default');
                $path = $image->store('shop_staffs/' . $this->staff->id, $disk);
                $data = [
                    'disk'      => $disk,
                    'file_path' => $path,
                    'file_name' => $image->getClientOriginalName(),
                    'mime_type' => $image->getMimeType(),
                    'file_size' => $image->getSize(),
                ];

                if ($old = $this->staff->image) {
                    Storage::disk($old->disk)->delete($old->file_path);
                }
                $this->staff->image()->create($data);
            }

            return $this->staff;
        });
    }
}
