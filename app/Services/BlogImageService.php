<?php

namespace App\Services;

use App\Models\Blog;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * Class BlogImageService.
 */
class BlogImageService
{
    public function store(Blog $blog, $image): string
    {
        $filename = (time() + rand(1, 10000)) . '_' . $image->getClientOriginalName();
        $path = 'blogs' . '/' . $blog->id . '/' . $filename;
        Storage::disk('public')->put($path, File::get($image));

        return $filename;
    }
}
