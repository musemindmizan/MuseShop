<?php

namespace App\Contracts;
use Illuminate\Http\UploadedFile;

interface ImageUploaderInterface
{
    public function upload(UploadedFile $file, string $directory): string;

    public function delete(?string $filename, string $directory): void;
}
