<?php

namespace App\Services;

use Exception;
use App\Models\Blog;
use App\Models\User;
use App\Models\BlogImage;
use App\Models\Comment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Repositories\BlogRepository;
use App\Repositories\LikeRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Repositories\CommentRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;

class BlogService
{
    protected BlogRepository $blogRepo;
    protected CommentRepository $commentRepo;
    protected LikeRepository $likeRepo;


    public function __construct(BlogRepository $blog, CommentRepository $comment, LikeRepository $like)
    {
        $this->blogRepo = $blog;
        $this->commentRepo = $comment;
        $this->likeRepo = $like;
    }

    private function getUser(): ?User
    {
        return Auth::user();
    }

    public function authorizedCheck(string $ability, Blog|Comment $model): void
    {
        if (Gate::denies($ability, $model)) {
            throw new Exception('unauthorized', 403);
        }
    }
    private function findBlogOrFail(int $id): ?Blog
    {
        $blog = $this->blogRepo->getById($id);
        if (!$blog) {
            throw new Exception('blog not found', 404);
        }
        return $blog;
    }

    public function getAllBlogs(): ?Collection
    {
        return $this->blogRepo->getAll();
    }

    public function getBlogDetail(int $id): ?Blog
    {
        try {
            $blog = $this->blogRepo->getById($id, ['category', 'user', 'comments']);
            if (!$blog) {
                throw new Exception('Blog not found', 404);
            }
            return $blog;
        } catch (Exception $e) {
            throw new Exception($e->getMessage() ?: 'failed get blog', $e->getCode() ?: 500);
        }
    }

    public function createBlog(array $data): ?Blog
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
            throw new Exception($e->getMessage() ?: 'failed to create blog', $e->getCode() ?: 500);
        }
    }

    public function updateBlog(Blog $blog, array $data, $imgPath = null): ?Blog
    {
        try {
            $this->authorizedCheck('update', $blog);
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
            $updatedBlog = $this->blogRepo->update($blog, $blogData);
            $updatedBlog->load(['category']);
            DB::commit();
            return $updatedBlog;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage() ?: 'failed to update', $e->getCode() ?: 500);
        }
    }

    public function removeBlog(int $blogId): void
    {
        try {
            $blog = $this->findBlogOrFail($blogId);
            $this->authorizedCheck('delete', $blog);
            if ($blog->thumbnail) {
                Storage::disk('public')->delete($blog->thumbnail);
            }
            $this->blogRepo->delete($blog);
        } catch (Exception $e) {
            throw new Exception($e->getMessage() ?: 'Blog failed to delete', $e->getCode() ?: 500);
        }
    }

    public function addComment(int $blogId, array $data): ?Comment
    {
        try {
            $blog = $this->findBlogOrFail($blogId);
            $user = $this->getUser();
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
            throw new Exception($e->getMessage() ?: 'Comment failed to create', $e->getCode() ?: 500);
        }
    }

    public function removeComment(int $blogId): void
    {
        try {
            $blog = $this->findBlogOrFail($blogId);
            $user = $this->getUser();
            $comment = $this->commentRepo->findByBlogAndUser($blog->id, $user->id);
            if (!$comment) {
                throw new Exception('Comment not found', 404);
            }
            $this->authorizedCheck('delete', $comment);
            $this->commentRepo->delete($comment);
        } catch (Exception $e) {
            throw new Exception($e->getMessage() ?: 'Comment failed to delete', $e->getCode() ?: 500);
        }
    }

    public function addLike(int $blogId): void
    {
        try {
            $blog = $this->findBlogOrFail($blogId);
            $user = $this->getUser();
            if ($this->likeRepo->exist($user, $blog)) {
                throw new Exception('You have already liked this blog', 400);
            };
            DB::beginTransaction();
            $this->likeRepo->create($user, $blog);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage() ?: 'Failed to like blog', $e->getCode() ?: 500);
        }
    }

    public function removeLike(int $blogId): void
    {
        try {
            $blog = $this->findBlogOrFail($blogId);

            $user = $this->getUser();
            if (!$this->likeRepo->exist($user, $blog)) {
                throw new Exception('You have not liked this blog', 400);
            }
            DB::beginTransaction();
            $this->likeRepo->delete($user, $blog);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage() ?: 'Failed to unlike this blog', $e->getCode() ?: 500);
        }
    }
}
