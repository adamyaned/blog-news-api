<?php

namespace App\Repositories\Interfaces;

interface LikeRepositoryInterface extends BaseRepositoryInterface
{
    public function blogLikeExists(int $blogId);

    public function articleLikeExists(int $articleId);
}
