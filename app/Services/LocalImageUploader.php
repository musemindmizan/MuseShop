<?php

namespace App\Services;

use App\Contracts\ImageUploaderInterface;
use Illuminate\Http\UploadedFile;

class LocalImageUploader implements ImageUploaderInterface
{
    public function upload(UploadedFile $file, string $directory): string
    {
        $filename = time() . '_' . uniqid() . '.' . $file->extension();

        $file->move(public_path($directory), $filename);

        return $filename;
    }

    public function delete(?string $filename, string $directory): void
    {
        if ($filename && file_exists(public_path($directory . '/' . $filename))) {
            unlink(public_path($directory . '/' . $filename));
        }
    }
}
