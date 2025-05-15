<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    private User $model;
    public function __construct(User $user)
    {
        $this->model = $user;
    }
    public function getAll(): ?Collection
    {
        return $this->model->all();
    }
    public function getUser(int $id): ?User
    {
        return $this->model->with(['followers', 'following'])
            ->where('id', $id)->first();
    }

    public function update(User $user, array $data): ?User
    {
        $user->profile()->update($data);
        return $user;
    }

    public function followers(User $user): ?Collection
    {
        $followers = $user->followers;
        return $followers;
    }

    public function following(User $user): ?Collection
    {
        $following = $user->following;
        return $following;
    }

    public function blogs(User $user): ?Collection
    {
        $blogs = $user->blogs;
        return $blogs;
    }

    public function likes(User $user): ?Collection
    {
        $userLikes = $user->likedBlogs;
        return $userLikes;
    }
}
