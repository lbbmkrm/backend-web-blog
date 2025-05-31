<?php

namespace App\Http\Controllers;

use Exception;
use App\Services\BlogService;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\Blogs\BlogResource;
use App\Http\Resources\Blogs\BlogsResource;
use App\Http\Requests\Blogs\StoreBlogRequest;
use App\Http\Requests\Blogs\UpdateBlogRequest;
use Illuminate\Http\JsonResponse;

class BlogController extends Controller
{
    private BlogService $blogService;

    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    //blog route
    public function index(): JsonResponse
    {
        try {
            $blogs = $this->blogService->getAllBlogs();
            $message = $blogs->isEmpty() ? 'Belum ada blog yang tersedia.'
                : 'Berhasil mengambil daftar blog.';
            return $this->successResponse(
                $message,
                BlogsResource::collection($blogs)
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }
    public function show(int $blogId): JsonResponse
    {
        try {
            $blog = $this->blogService->getBlogDetail($blogId);
            return $this->successResponse(
                'Berhasil mengambil detail blog.',
                new BlogResource($blog)
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function store(StoreBlogRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $blog = $this->blogService->createBlog($validated);

            return $this->successResponse(
                'Blog berhasil dibuat.',
                new BlogResource($blog),
                201
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }


    public function update(UpdateBlogRequest $request, int $blogId): JsonResponse
    {
        try {
            $blog = $this->blogService->getBlogDetail($blogId);
            $validated = $request->validated();
            $thumbnail = $request->file('thumbnail');
            $updatedBlog = $this->blogService->updateBlog($blog, $validated, $thumbnail);
            return $this->successResponse(
                'Blog berhasil diperbarui.',
                new BlogResource($updatedBlog)
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->blogService->removeBlog($id);

            return $this->successResponse('Blog berhasil dihapus.');
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    //comment route
    public function addComment(CommentRequest $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            $comment = $this->blogService->addComment($id, $validated);

            return $this->successResponse(
                'Komentar berhasil ditambahkan.',
                new CommentResource($comment),
                201
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function deleteComment(int $id): JsonResponse
    {
        try {
            $this->blogService->removeComment($id);
            return $this->successResponse('Komentar berhasil dihapus.');
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }


    //like route
    public function like(int $id): JsonResponse
    {
        try {
            $this->blogService->addLike($id);
            return $this->successResponse(
                'Blog berhasil disukai.',
                [],
                201
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function unLike(int $id): JsonResponse
    {
        try {
            $this->blogService->removeLike($id);
            return $this->successResponse('Suka pada blog berhasil dihapus.');
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }
}
