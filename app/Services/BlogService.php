<?php

namespace App\Services;

use App\Notifications\BlogLiked;
use App\Repositories\TagRepository;
use Exception;
use App\Models\Blog;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\BlogCommented;
use App\Repositories\BlogRepository;
use App\Repositories\LikeRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Repositories\CommentRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Auth\Access\AuthorizationException;

use function PHPUnit\Framework\isNull;

class BlogService
{
    protected BlogRepository $blogRepo;
    protected CommentRepository $commentRepo;
    protected LikeRepository $likeRepo;
    protected TagRepository $tagRepo;


    public function __construct(
        BlogRepository $blog,
        CommentRepository $comment,
        LikeRepository $like,
        TagRepository $tagRepository
    ) {
        $this->blogRepo = $blog;
        $this->commentRepo = $comment;
        $this->likeRepo = $like;
        $this->tagRepo = $tagRepository;
    }

    private function getUser(): ?User
    {
        return Auth::guard('sanctum')->user();
    }
    private function isAuthorized(string $ability, $arguments = [])
    {
        try {
            Gate::authorize($ability, $arguments);
        } catch (AuthorizationException $e) {
            throw new Exception('Unauthorized!', 403);
        }
    }

    public function getAllBlogs(): ?Collection
    {
        return $this->blogRepo->getAll();
    }

    public function getBlogDetail(int $id): ?Blog
    {
        try {
            $blog = $this->blogRepo->getById($id, ['category', 'user', 'comments']);
            return $blog;
        } catch (Exception $e) {
            throw new Exception($e->getMessage() ?: 'Gagal mengambil detail blog.', $e->getCode() ?: 500);
        }
    }

    public function createBlog(array $requestData): ?Blog
    {
        try {
            DB::beginTransaction();
            $user = $this->getUser();

            $slug = Str::slug($requestData['title']);
            $originalSlug = $slug;
            $counter = 1;

            while ($this->blogRepo->model->where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $blogData = [
                'user_id' => $user->id,
                'category_id' => $requestData['category_id'],
                'title' => $requestData['title'],
                'content' => $requestData['content'],
                'description' => $requestData['description'] ?? null,
                'slug' => $slug,
            ];

            if (isset($requestData['thumbnail']) && $requestData['thumbnail'] instanceof \Illuminate\Http\UploadedFile) {
                $blogData['thumbnail'] = $requestData['thumbnail']->store('thumbnails', 'public');
            } else {
                $blogData['thumbnail'] = null;
            }

            $blog = $this->blogRepo->create($blogData);

            if (isset($requestData['tags']) && !empty($requestData['tags'])) {
                $tagIds = [];
                foreach ($requestData['tags'] as $tagName) {
                    $tag = $this->tagRepo->getOrCreate(['name' => $tagName], ['user_id' => $user->id]);
                    $tagIds[] = $tag->id;
                }
                $blog->tags()->attach($tagIds, ['created_at' => now()]);
            }

            DB::commit();
            return $blog->load('category');
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Gagal membuat blog: ' . $e->getMessage());
        }
    }


    public function updateBlog(Blog $blog, array $requestData, $imgPath = null): ?Blog
    {
        try {
            $this->isAuthorized('update', $blog);
            DB::beginTransaction();

            $thumbnailPath = $blog->thumbnail;

            if ($imgPath && $imgPath instanceof \Illuminate\Http\UploadedFile) {
                if ($blog->thumbnail) {
                    Storage::disk('public')->delete($blog->thumbnail);
                }
                $thumbnailPath = $imgPath->store('thumbnails', 'public');
            } elseif (isset($requestData['remove_thumbnail']) && $requestData['remove_thumbnail']) {
                if ($blog->thumbnail) {
                    Storage::disk('public')->delete($blog->thumbnail);
                }
                $thumbnailPath = null;
            }

            $slug = $blog->slug;
            if ($requestData['title'] !== $blog->title) {
                $newSlug = Str::slug($requestData['title']);
                $originalSlug = $newSlug;
                $counter = 1;

                while ($this->blogRepo->model->where('slug', $newSlug)
                    ->where('id', '!=', $blog->id)
                    ->exists()
                ) {
                    $newSlug = $originalSlug . '-' . $counter;
                    $counter++;
                }
                $slug = $newSlug;
            }

            $blogData = [
                'category_id' => $requestData['category_id'],
                'title' => $requestData['title'],
                'content' => $requestData['content'],
                'description' => $requestData['description'] ?? null,
                'slug' => $slug,
                'thumbnail' => $thumbnailPath
            ];

            $updatedBlog = $this->blogRepo->update($blog, $blogData);

            if (isset($requestData['tags'])) {
                if (empty($requestData['tags'])) {
                    $updatedBlog->tags()->detach();
                } else {
                    $tagIds = [];
                    $user = $this->getUser();

                    foreach ($requestData['tags'] as $tagName) {
                        $tag = $this->tagRepo->getOrCreate(
                            ['name' => $tagName],
                            ['user_id' => $user->id]
                        );
                        $tagIds[] = $tag->id;
                    }

                    $updatedBlog->tags()->sync($tagIds);
                }
            }

            DB::commit();
            return $updatedBlog->load(['category', 'tags']);
        } catch (Exception $e) {
            DB::rollBack();

            if ($imgPath && isset($thumbnailPath) && $thumbnailPath !== $blog->thumbnail) {
                Storage::disk('public')->delete($thumbnailPath);
            }

            throw new Exception('Gagal memperbarui blog: ' . $e->getMessage());
        }
    }

    public function removeBlog(int $blogId): void
    {
        try {
            $blog = $this->blogRepo->getById($blogId);
            $this->isAuthorized('delete', $blog);
            if ($blog->thumbnail) {
                Storage::disk('public')->delete($blog->thumbnail);
            }
            $this->blogRepo->delete($blog);
        } catch (Exception $e) {
            throw new Exception($e->getMessage() ?: 'Gagal menghapus blog.', $e->getCode() ?: 500);
        }
    }

    public function addComment(int $blogId, array $data): ?Comment
    {
        try {
            $blog = $this->blogRepo->getById($blogId);
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

            if ($blog->user_id !== $user->id) {
                try {
                    $blog->user->notify(new BlogCommented($comment, $blog, $user));
                } catch (Exception $e) {
                    Log::error('Failed to send notification', [
                        'blog_id' => $blog->id,
                        'user_id' => $user->id,
                        'commenter_id' => $user->id,
                        'comment_id' => $comment->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            return $comment;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage() ?: 'Gagal menambahkan komentar.', $e->getCode() ?: 500);
        }
    }

    public function removeComment(int $commentId): void
    {
        try {
            $comment = $this->commentRepo->find($commentId);
            $this->isAuthorized('delete', $comment);
            $this->commentRepo->delete($comment);
        } catch (Exception $e) {
            throw new Exception($e->getMessage() ?: 'Gagal menghapus komentar.', $e->getCode() ?: 500);
        }
    }

    public function addLike(int $blogId): void
    {
        try {
            $blog = $this->blogRepo->getById($blogId);
            $user = $this->getUser();
            if ($this->likeRepo->exist($user, $blog)) {
                throw new Exception('Anda sudah menyukai blog ini', 400);
            };
            DB::beginTransaction();
            $this->likeRepo->create($user, $blog);
            DB::commit();
            if ($blog->user_id !== $user->id) {
                try {
                    $blog->user->notify(new BlogLiked($blog, $user));
                } catch (Exception $e) {
                    Log::error('Gagal mengirim notifikasi', [
                        'blog_id' => $blog->id,
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage() ?: 'Gagal menyukai blog.', $e->getCode() ?: 500);
        }
    }

    public function removeLike(int $blogId): void
    {
        try {
            $blog = $this->blogRepo->getById($blogId);

            $user = $this->getUser();
            if (!$this->likeRepo->exist($user, $blog)) {
                throw new Exception('Anda belum menyukai blog ini.', 400);
            }
            DB::beginTransaction();
            $this->likeRepo->delete($user, $blog);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage() ?: 'Gagal menghapus like', $e->getCode() ?: 500);
        }
    }
}
