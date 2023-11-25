<?php

namespace App\Repositories;

use App\Models\Article;
use App\Repositories\Interfaces\ArticleRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ArticleRepository extends BaseRepository implements ArticleRepositoryInterface
{
    public function __construct(Article $model)
    {
        parent::__construct($model);
    }

    public function getArticles(string|null $search = null): LengthAwarePaginator
    {
        return $this->model
            ->with(['likes', 'comments'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('description', 'LIKE', "%$search%")
                        ->orWhere('name', 'LIKE', "%$search%")
                        ->orWhereJsonContains('tags', $search);
                });
            })
            ->paginate(20);
    }
}
