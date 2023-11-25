<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImageUploadRequest;
use App\Http\Resources\SuccessResource;
use App\Services\ImageService;

class ImageController extends Controller
{
    public function __construct(
        private ImageService $imageService
    )
    {
    }

    public function store(ImageUploadRequest $request): SuccessResource
    {
        $data = $request->validated();
        $this->imageService->uploadImage($data);

        return SuccessResource::make([
            'message' => 'Image uploaded successfully'
        ]);
    }
}

