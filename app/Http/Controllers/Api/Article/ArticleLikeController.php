<?php

namespace App\Http\Controllers\Api\Article;

use App\Http\Controllers\Controller;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Models\Article;
use App\Repositories\Interfaces\LikeRepositoryInterface;

class ArticleLikeController extends Controller
{
    public function __construct(
        private LikeRepositoryInterface $likeRepository
    )
    {
    }

    public function store(Article $article): SuccessResource|ErrorResource
    {
        if (!$this->likeRepository->articleLikeExists($article->id)) {
            return ErrorResource::make([
                'message' => 'Article already liked'
            ]);
        }

        $like = $this->likeRepository->create([
            'user_id' => auth()->id()
        ]);

        $article->likes()->attach($like->id);

        return SuccessResource::make([
            'message' => 'Article liked'
        ]);
    }
}
