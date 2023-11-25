<?php

namespace App\Http\Controllers\Api\Article;

use App\Http\Controllers\Controller;
use App\Http\Requests\Article\StoreArticleRequest;
use App\Http\Resources\Article\ArticleResource;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\SuccessResource;
use App\Repositories\Interfaces\ArticleRepositoryInterface;
use App\Services\ArticleImageService;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
        private ArticleImageService        $articleImageService,
    )
    {
    }

    public function index(Request $request): PaginationResource
    {
        $articles = $this->articleRepository->getArticles($request->get('search', null));

        return PaginationResource::make([
            'data' => ArticleResource::collection($articles->items()),
            'pagination' => $articles
        ]);

    }

    public function store(StoreArticleRequest $request): SuccessResource
    {
        $data = $request->validated();
        $article = $this->articleRepository->create($data);

        $image = $this->articleImageService->store($article, $data['image']);

        $this->articleRepository->update($article->id, [
            'image' => $image
        ]);

        return SuccessResource::make([
            'message' => 'Blog created successfully'
        ]);
    }
}
