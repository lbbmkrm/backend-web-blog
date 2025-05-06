<?php

namespace App\Http\Controllers;

use Exception;
use App\Services\BlogService;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\Blogs\BlogResource;
use App\Http\Resources\Blogs\BlogsResource;
use App\Http\Requests\Blogs\StoreBlogRequest;
use App\Http\Requests\Blogs\UpdateBlogRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * @method void authorize(string $ability, mixed $arguments)
 */
class BlogController extends Controller
{
    use AuthorizesRequests;
    private $blogService;

    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    //blog route
    public function index()
    {
        $blogs = $this->blogService->getAllBlogs();

        return BlogsResource::collection($blogs);
    }
    public function show(int $blogId)
    {
        try {
            $blog = $this->blogService->getSingleBlog($blogId);
            return new BlogResource($blog);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }

    public function store(StoreBlogRequest $request)
    {
        try {

            $validated = $request->validated();

            $blog = $this->blogService->createBlog($validated);

            return response()->json([
                'message' => 'Blog created successfully',
                'data' => new BlogResource($blog->load(['user', 'category']))
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }


    public function update(UpdateBlogRequest $request, int $blogId)
    {
        try {
            $blog = $this->blogService->getSingleBlog($blogId);
            $this->authorize('update', $blog);
            $validated = $request->validated();
            $thumbnail = $request->file('thumbnail');
            $updatedBlog = $this->blogService->updateBlog($blog, $validated, $thumbnail);
            return response()->json([
                'message' => 'Blog updated successfully',
                'data' => new BlogResource($updatedBlog)
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }

    public function destroy(int $id)
    {
        try {
            $blog = $this->blogService->getSingleBlog($id);
            $this->authorize('delete', $blog);

            $this->blogService->deleteBlog($id);

            return response()->json([
                'message' => 'Blog deleted successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }

    //comment route
    public function addComment(CommentRequest $request, int $id)
    {
        try {
            $validated = $request->validated();
            $comment = $this->blogService->createComment($id, $validated);

            return response()->json([
                'message' => 'Comment added successfully',
                'data' => new CommentResource($comment)
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ], $e->getCode() ?: 500);
        }
    }

    public function deleteComment(int $id)
    {
        try {
            $this->blogService->deleteComment($id);
            return response()->json([
                'message' => 'Comment deleted successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Comment failed to delete'
            ], $e->getCode() ?: 500);
        }
    }


    //like route
    public function like(int $id)
    {
        Log::info('createLike called', ['blogId' => $id]);
        try {
            $this->blogService->addLike($id);

            return response()->json([
                'message' => 'Blog liked successfully'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }

    public function unLike(int $id)
    {
        try {
            $this->blogService->removeLike($id);
            return response()->json([
                'message' => 'Success unlike'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }
}
