<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ImageService
{
    public function uploadImage($base64Image)
    {
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
        $imageInfo = getimagesizefromstring($imageData);

        if (!$imageInfo) {
            throw new BadRequestHttpException('Invalid image format');
        }

        $extension = image_type_to_extension($imageInfo[2]);

        if (!in_array($extension, ['jpg', 'png', 'svg', 'pdf',])) {
            throw new BadRequestHttpException('Invalid image format');
        }

        $currentTime = now()->format('YmdHis');
        $filename = $currentTime . Str::random(20) . $extension;

        Storage::disk('public')->put($filename, $imageData);
    }
}
