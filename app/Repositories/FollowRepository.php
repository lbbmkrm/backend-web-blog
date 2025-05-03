<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Follow;

class FollowRepository
{
    private $followModel;
    public function __construct(Follow $follow)
    {
        $this->followModel = $follow;
    }

    public function create(User $follower, User $followed)
    {
        $follower->following()->attach($followed);
    }

    public function delete(User $follower, User $followed)
    {
        $follower->following()->detach($followed);
    }

    public function exist(int $userId, int $followerId): bool
    {
        return $this->followModel->where('user_id', $userId)
            ->where('follower_id', $followerId)->exists();
    }

    public function doesntExist(int $userId, int $followerId): bool
    {
        return $this->followModel->where('user_id', $userId)
            ->where('follower_id', $followerId)->doesntExist();
    }
}
