<?php

namespace App\Repositories;

use App\Models\Blog;
use App\Repositories\Interfaces\BlogRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class BlogRepository extends BaseRepository implements BlogRepositoryInterface
{
    public function __construct(Blog $model)
    {
        parent::__construct($model);
    }

    public function getBlogs(string|null $search = null): LengthAwarePaginator
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
