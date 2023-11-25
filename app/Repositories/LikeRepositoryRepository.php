<?php

namespace App\Repositories;

use App\Models\Like;
use App\Repositories\Interfaces\LikeRepositoryInterface;
use Illuminate\Support\Facades\DB;

class LikeRepositoryRepository extends BaseRepository implements LikeRepositoryInterface
{
    public function __construct(Like $model)
    {
        parent::__construct($model);
    }

    public function bloglikeexists(int $blogId)
    {
        return DB::table('likes')
            ->join('blog_like', 'likes.id', '=', 'blog_like.like_id')
            ->where('blog_like.blog_id', $blogId)
            ->where('likes.user_id', auth()->id())
            ->exists();
    }

    public function articlelikeexists(int $articleId)
    {
        return DB::table('likes')
            ->join('article_like', 'likes.id', '=', 'article_like.like_id')
            ->where('article_like.article_id', $articleId)
            ->where('likes.user_id', auth()->id())
            ->exists();
    }
}
