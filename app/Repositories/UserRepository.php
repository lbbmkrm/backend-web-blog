<?php

namespace App\Repositories;

use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    private $model;
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
        return $this->model->find($id);
    }

    public function update(User $user, array $data): User
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
}
