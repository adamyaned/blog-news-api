<?php

namespace App\Http\Controllers\Api\Article;

use App\Http\Controllers\Controller;
use App\Http\Requests\Article\StoreArticleCommentRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Models\Article;
use App\Repositories\Interfaces\CommentRepositoryInterface;

class ArticleCommentController extends Controller
{
    public function __construct(
        private CommentRepositoryInterface $commentRepository
    )
    {
    }

    public function store(StoreArticleCommentRequest $request, Article $article): SuccessResource|ErrorResource
    {
        $data = $request->validated();

        $comment = $this->commentRepository->create([
            'user_id' => auth()->id(),
            'article_id' => $article->id,
            'text' => $data['text']
        ]);

        $article->comments()->attach($comment->id);

        return SuccessResource::make([
            'message' => 'Comment successfully created'
        ]);
    }
}
