<?php

namespace App\Services\Media;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadImageService
{
    public function attach(UploadedFile $file, Model $model, string $directory): void
    {
        $disk = config('filesystems.default');
        $path = $file->store($directory . '/' . $model->id, $disk);
        $data = [
            'disk'      => $disk,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
        ];

        if ($existing = $model->image) {
            Storage::disk($existing->disk)->delete($existing->file_path);
            $model->image()->updateOrCreate([], $data);
        } else {
            $model->image()->create($data);
        }
    }
}
