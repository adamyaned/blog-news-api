<?php

namespace App\Repositories\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface BlogRepositoryInterface extends BaseRepositoryInterface
{
    public function getBlogs(string|null $search = null): LengthAwarePaginator;
}
