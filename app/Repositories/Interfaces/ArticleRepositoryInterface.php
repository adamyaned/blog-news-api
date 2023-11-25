<?php

namespace App\Repositories\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface ArticleRepositoryInterface extends BaseRepositoryInterface
{
    public function getArticles(string|null $search = null): LengthAwarePaginator;
}
