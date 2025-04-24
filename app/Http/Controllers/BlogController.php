<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Blogs\StoreBlogRequest;
use App\Http\Requests\Blogs\UpdateBlogRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\Blogs\BlogResource;
use App\Http\Resources\Blogs\BlogsResource;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * @method void authorize(string $ability, mixed $arguments)
 */
class BlogController extends Controller
{
    use AuthorizesRequests;
    public function getCategories()
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            return response()->json([
                'message' => 'No categories found',
                'data' => []
            ], 200);
        }

        return CategoryResource::collection($categories);
    }

    public function getAllBlogs()
    {
        $blogs = Blog::all();

        return BlogsResource::collection($blogs);
    }
    public function getSingleBlog(int $blogId)
    {
        $blog = Blog::with(['user', 'category'])->find($blogId);
        if (!$blog) {
            return response()->json([
                'message' => 'Blog not found',
            ], 404);
        }

        return new BlogResource($blog);
    }

    public function blogCreate(StoreBlogRequest $request)
    {
        $validated = $request->validated();

        $thumbnail = '';
        try {
            DB::beginTransaction();

            if ($request->hasFile('thumbnail')) {
                $thumbnail = $request->file('thumbnail')->store('thumbnails', 'public');
            }

            $slug = Str::slug($validated['title']);
            $user = Auth::user();

            $blog = Blog::create([
                'user_id' => $user->id,
                'category_id' => $validated['category_id'],
                'title' => $validated['title'],
                'content' => $validated['content'],
                'description' => $validated['description'],
                'slug' => $slug,
                'thumbnail' => $thumbnail,
                'created_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Blog created successfully',
                'data' => new BlogResource($blog->load(['user', 'category']))
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Blog failed to create',
            ], 500);
        }
    }


    public function blogUpdate(UpdateBlogRequest $request, int $blogId)
    {
        $blog = Blog::find($blogId);
        if (!$blog) {
            return response()->json([
                'message' => 'Blog not found',
            ], 404);
        }
        $this->authorize('update', $blog);

        $validated = $request->validated();

        if ($request->hasFile('thumbnail')) {
            if ($blog->thumbnail) {
                Storage::disk('public')->delete($blog->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $blog->category_id = $validated['category_id'];
        $blog->title = $validated['title'];
        $blog->content = $validated['content'];
        $blog->description = $validated['description'];
        $blog->thumbnail = $validated['thumbnail'];
        $blog->updated_at = now();

        $blog->save();

        return response()->json([
            'message' => 'Blog updated successfully',
            'blog' => new BlogResource($blog)
        ]);
    }

    public function blogDelete(int $blogId)
    {
        $blog = Blog::find($blogId);
        if (!$blog) {
            return response()->json([
                'message' => 'Blog not found',
            ], 404);
        }

        try {
            $this->authorize('delete', $blog);

            if ($blog->thumbnail) {
                Storage::disk('public')->delete($blog->thumbnail);
            }
            $blog->delete();

            return response()->json([
                'message' => 'Blog deleted successfully',

            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Blog delete to failed',
            ]);
        }
    }
}
