<?php

namespace App\Repositories;

use App\Models\Blog;
use App\Models\Like;
use App\Models\User;
use Exception;

class LikeRepository
{
    public function create(User $user, Blog $blog)
    {
        $user->likedBlogs()->attach($blog->id);
    }

    public function delete(User $user, Blog $blog)
    {
        $user->likedBlogs()->detach($blog->id);
    }

    public function exist(User $user, Blog $blog): bool
    {
        return $user->likedBlogs()->where('blog_id', '=', $blog->id)->exists();
    }
}
