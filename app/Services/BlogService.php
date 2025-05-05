<?php

namespace App\Services;

use Exception;
use App\Models\Blog;
use App\Models\BlogImage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Repositories\BlogRepository;
use App\Repositories\LikeRepository;
use Illuminate\Support\Facades\Auth;
use App\Repositories\CommentRepository;
use Illuminate\Support\Facades\Storage;

class BlogService
{
    protected $blogRepo;
    protected $commentRepo;
    protected $likeRepo;


    public function __construct(BlogRepository $blog, CommentRepository $comment, LikeRepository $like)
    {
        $this->blogRepo = $blog;
        $this->commentRepo = $comment;
        $this->likeRepo = $like;
    }

    private function getUser()
    {
        return Auth::user();
    }

    public function getAllBlogs()
    {
        return $this->blogRepo->getAll();
    }

    public function getSingleBlog(int $id): ?Blog
    {
        $blog = $this->blogRepo->findWithRelations($id);
        if (!$blog) {
            throw new Exception('Blog not found', 404);
        }
        return $blog;
    }

    public function createBlog(array $data)
    {
        try {
            DB::beginTransaction();

            $slug = Str::slug($data['title']);
            $user = $this->getUser();
            $blogData = [
                'user_id' => $user->id,
                'category_id' => $data['category_id'],
                'title' => $data['title'],
                'content' => $data['content'],
                'description' => $data['description'] ?? null,
                'slug' => $slug,
                'created_at' => now()
            ];

            if (isset($data['thumbnail'])) {
                $blogData['thumbnail'] = $data['thumbnail']->store('thumbnails', 'public');
            } else {
                $blogData['thumbnail'] = null;
            }

            $blog = $this->blogRepo->create($blogData);
            DB::commit();
            return $blog;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('failed to create blog', 500);
        }
    }

    public function updateBlog(Blog $blog, array $data, $imgPath = null)
    {
        try {
            DB::beginTransaction();
            if ($imgPath && $blog->thumbnail) {
                Storage::disk('public')->delete($blog->thumbnail);
                $data['thumbnail'] = $imgPath->store('thumbnails', 'public');
            }

            $blogData = [
                'category_id' => $data['category_id'],
                'title' => $data['title'],
                'content' => $data['content'],
                'description' => $data['description'],
                'thumbnail' => $imgPath ?? $blog->thumbnail
            ];
            DB::commit();
            $updatedBlog = $this->blogRepo->update($blog, $blogData);
            $updatedBlog->load(['category']);
            return $updatedBlog;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    public function deleteBlog(int $blogId)
    {
        $blog = $this->blogRepo->find($blogId);
        if (!$blog) {
            throw new Exception('Blog not found', 404);
        }

        try {
            if ($blog->thumbnail) {
                Storage::disk('public')->delete($blog->thumbnail);
            }
            $this->blogRepo->delete($blog);
        } catch (Exception $e) {
            throw new Exception('Blog failed to delete', 500);
        }
    }

    public function createComment(int $blogId, array $data)
    {
        $blog = $this->blogRepo->find($blogId);
        $user = Auth::user();

        try {
            DB::beginTransaction();
            $commentData = [
                'blog_id' => $blog->id,
                'user_id' => $user->id,
                'content' => $data['content'],
                'created_at' => now()
            ];
            $comment = $this->commentRepo->create($commentData);
            DB::commit();

            return $comment;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Comment failed to create', $e->getCode() ?: 500);
        }
    }

    public function deleteComment(int $blogId)
    {

        $blog = $this->blogRepo->find($blogId);
        $user = $this->getUser();
        $comment = $this->commentRepo->where($blog->id, $user->id);
        if (!$comment) {
            throw new Exception('Comment not found', 404);
        }
        try {
            $this->commentRepo->delete($comment);
        } catch (Exception $e) {
            throw new Exception('Comment failed to delete');
        }
    }

    public function addLike(int $blogId)
    {
        $blog = $this->blogRepo->find($blogId);
        if (!$blog) {
            throw new Exception('Blog not found', 404);
        }
        $user = $this->getUser();
        if ($this->likeRepo->exist($user, $blog)) {
            throw new Exception('You have already liked this blog', 400);
        };

        try {
            DB::beginTransaction();
            $this->likeRepo->create($user, $blog);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to like blog', 500);
        }
    }

    public function removeLike(int $blogId)
    {
        $blog = $this->blogRepo->find($blogId);
        if (!$blog) {
            throw new Exception('Blog not found', 404);
        }

        $user = $this->getUser();
        if (!$this->likeRepo->exist($user, $blog)) {
            throw new Exception('You have not liked this blog', 400);
        }

        try {
            DB::beginTransaction();
            $this->likeRepo->delete($user, $blog);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to unlike this blog');
        }
    }
}
