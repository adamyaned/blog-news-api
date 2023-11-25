<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\StoreBlogRequest;
use App\Http\Resources\Blog\BlogResource;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\SuccessResource;
use App\Repositories\Interfaces\BlogRepositoryInterface;
use App\Services\BlogImageService;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function __construct(
        private BlogRepositoryInterface $blogRepository,
        private BlogImageService        $blogImageService,
    )
    {
    }

    public function index(Request $request): PaginationResource
    {
        $blogs = $this->blogRepository->getBlogs($request->get('search', null));

        return PaginationResource::make([
            'data' => BlogResource::collection($blogs->items()),
            'pagination' => $blogs
        ]);

    }

    public function store(StoreBlogRequest $request): SuccessResource
    {
        $data = $request->validated();
        $blog = $this->blogRepository->create($data);

        $image = $this->blogImageService->store($blog, $data['image']);

        $this->blogRepository->update($blog->id, [
            'image' => $image
        ]);

        return SuccessResource::make([
            'message' => 'Blog created successfully'
        ]);
    }
}
