<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\StoreBlogCommentRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Models\Article;
use App\Models\Blog;
use App\Repositories\Interfaces\CommentRepositoryInterface;

class BlogCommentController extends Controller
{
    public function __construct(
        private CommentRepositoryInterface $commentRepository
    )
    {
    }

    public function store(StoreBlogCommentRequest $request, Blog $blog): SuccessResource|ErrorResource
    {
        $data = $request->validated();

        $comment = $this->commentRepository->create([
            'user_id' => auth()->id(),
            'blog_id' => $blog->id,
            'text' => $data['text']
        ]);

        $blog->comments()->attach($comment->id);

        return SuccessResource::make([
            'message' => 'Comment successfully created'
        ]);
    }
}
