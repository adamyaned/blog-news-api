<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * Class ArticleImageService.
 */
class ArticleImageService
{
    public function store(Article $article, $image): string
    {
        $filename = (time() + rand(1, 10000)) . '_' . $image->getClientOriginalName();
        $path = 'articles' . '/' . $article->id . '/' . $filename;
        Storage::disk('public')->put($path, File::get($image));

        return $filename;
    }
}
