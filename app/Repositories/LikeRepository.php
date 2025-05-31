<?php

namespace App\Repositories;

use App\Models\Blog;
use App\Models\Like;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use InvalidArgumentException;
use PDOException;

class LikeRepository
{
    protected Like $model;

    public function __construct(Like $like)
    {
        $this->model = $like;
    }
    public function create(User $user, Blog $blog): void
    {
        try {
            $user->likedBlogs()->attach($blog->id);
        } catch (QueryException | PDOException $e) {
            throw new Exception('Terjadi kesalahan pada database saat menambahkan like.', 500);
        } catch (Exception $e) {
            throw new Exception('Terjadi kesalahan saat menambahkan like.', 500);
        }
    }

    public function delete(User $user, Blog $blog): void
    {
        try {
            $user->likedBlogs()->detach($blog->id);
        } catch (QueryException | PDOException $e) {
            throw new Exception('Terjadi kesalahan pada database saat menghapus like.', 500);
        } catch (Exception $e) {
            throw new Exception('Terjadi kesalahan saat menghapus like.', 500);
        }
    }

    public function exist(User $user, Blog $blog): bool
    {
        try {
            return $user->likedBlogs()->where('blog_id', $blog->id)->exists();
        } catch (QueryException | PDOException $e) {
            throw new Exception('Terjadi kesalahan pada database saat memeriksa status like.', 500);
        } catch (Exception $e) {
            throw new Exception('Terjadi kesalahan saat memeriksa status like.', 500);
        }
    }
}
