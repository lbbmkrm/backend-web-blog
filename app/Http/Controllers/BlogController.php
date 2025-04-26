<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Services\BlogService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\Blogs\StoreBlogRequest;
use App\Http\Requests\Blogs\UpdateBlogRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\Blogs\BlogResource;
use App\Http\Resources\Blogs\BlogsResource;
use App\Repositories\CategoryRepository;
use Exception;

/**
 * @method void authorize(string $ability, mixed $arguments)
 */
class BlogController extends Controller
{
    use AuthorizesRequests;
    private $categoryRepo;
    private $blogService;

    public function __construct(BlogService $blogService, CategoryRepository $categoryRepository)
    {
        $this->categoryRepo = $categoryRepository;
        $this->blogService = $blogService;
    }

    //category route
    public function getCategories()
    {
        $categories = $this->categoryRepo->getAll();

        if ($categories->isEmpty()) {
            return response()->json([
                'message' => 'No categories found',
                'data' => []
            ], 200);
        }

        return CategoryResource::collection($categories);
    }


    //blog route
    public function getAllBlogs()
    {
        $blogs = $this->blogService->getAllBlogs();

        return BlogsResource::collection($blogs);
    }
    public function getSingleBlog(int $blogId)
    {
        try {
            $blog = $this->blogService->getSingleBlog($blogId);
            return new BlogResource($blog);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function blogCreate(StoreBlogRequest $request)
    {
        try {
            $validated = $request->validated();
            $imgPath = $request->file('thumbnail');
            $blog = $this->blogService->createBlog($validated, $imgPath);

            return response()->json([
                'message' => 'Blog created successfully',
                'data' => new BlogResource($blog->load(['user', 'category'], 201))
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }


    public function blogUpdate(UpdateBlogRequest $request, int $blogId)
    {
        try {
            $blog = $this->blogService->getSingleBlog($blogId);
            $this->authorize('update', $blog);
            $validated = $request->validated();
            $thumbnail = $request->file('thumbnail');
            $updatedBlog = $this->blogService->updateBlog($blog->id, $validated, $thumbnail);
            return response()->json([
                'message' => 'Blog updated successfully',
                'data' => new BlogResource($updatedBlog)
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Blog failed to update'
            ], $e->getCode());
        }
    }

    public function blogDelete(int $blogId)
    {
        try {
            $blog = $this->blogService->getSingleBlog($blogId);
            $this->authorize('delete', $blog);

            $this->blogService->deleteBlog($blogId);

            return response()->json([
                'message' => 'Blog deleted successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    //comment route
    public function addComment(CommentRequest $request, int $blogId)
    {
        try {
            $validated = $request->validated();
            $comment = $this->blogService->createComment($blogId, $validated);

            return response()->json([
                'message' => 'Comment added successfully',
                'data' => new CommentResource($comment)
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ], $e->getCode());
        }
    }

    public function deleteComment(int $blogId)
    {
        try {
            $this->blogService->deleteComment($blogId);
            return response()->json([
                'message' => 'Comment deleted successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Comment failed to delete'
            ], $e->getCode());
        }
    }
}
