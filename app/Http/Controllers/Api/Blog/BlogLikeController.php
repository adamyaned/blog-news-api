<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Models\Blog;
use App\Repositories\Interfaces\LikeRepositoryInterface;

class BlogLikeController extends Controller
{
    public function __construct(
        private LikeRepositoryInterface $likeRepository
    )
    {
    }

    public function store(Blog $blog): SuccessResource|ErrorResource
    {
        if ($this->likeRepository->blogLikeExists($blog->id)) {
            return ErrorResource::make([
                'message' => 'Blog already liked'
            ]);
        }

        $like = $this->likeRepository->create([
            'user_id' => auth()->id()
        ]);

        $blog->likes()->attach($like->id);

        return SuccessResource::make([
            'message' => 'Blog liked'
        ]);
    }
}
