<?php

namespace App\UseCases\Operator\ShopStaff;

use App\Models\ShopStaff;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeleteShopStaffUseCase
{
    public function __invoke(ShopStaff $staff): bool
    {
        return DB::transaction(function () use ($staff) {
            $staff->load(['image', 'user']);
            if ($staff->image) {
                if (Storage::disk($staff->image->disk)->exists($staff->image->file_path)) {
                    Storage::disk($staff->image->disk)->delete($staff->image->file_path);
                }
                $staff->image->delete();
            }
            $staff->user->delete();
            $staff->delete();

            return true;
        });
    }
}
