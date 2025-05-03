<?php

namespace App\Services;

use Exception;
use App\Models\User;
use App\Repositories\FollowRepository;
use Illuminate\Support\Facades\DB;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class UserService
{
    protected $userRepo;
    protected $followRepo;
    public function __construct(UserRepository $userRepository, FollowRepository $followRepository)
    {
        $this->userRepo = $userRepository;
        $this->followRepo = $followRepository;
    }


    public function getAllUsers(): Collection
    {
        return $this->userRepo->getAll();
    }

    public function getCurrentUser(): User
    {
        return Auth::user();
    }
    public function getUser(int $userId): User
    {
        $user = $this->userRepo->getUser($userId);
        if (!$user) {
            throw new Exception('User not found', 404);
        }
        return $user;
    }
    public function selfId(int $userId, int $currentUserId): bool
    {
        if ($userId === $currentUserId) {
            return true;
        }
        return false;
    }

    public function follow(int $userId)
    {
        $currentUser = $this->getCurrentUser();
        $user = $this->userRepo->getUser($userId);
        if ($this->selfId($user->id, $currentUser->id)) {
            throw new Exception('Cannot follow yourself', 400);
        }
        if ($this->followRepo->exist($user->id, $currentUser->id)) {
            throw new Exception('the current user has followed this user', 400);
        }
        try {
            DB::beginTransaction();
            $this->followRepo->create($currentUser, $user);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Following failed', 500);
        }
    }

    public function unFollow(int $userId)
    {
        $user = $this->getUser($userId);
        $currentUser = $this->getCurrentUser();
        if ($this->selfId($userId, $currentUser->id)) {
            throw new Exception('You cannot unfollow yourself.', 400);
        }
        if ($this->followRepo->doesntExist($user->id, $currentUser->id)) {
            throw new Exception('current users have not followed', 400);
        }
        try {
            DB::beginTransaction();
            $this->followRepo->delete($currentUser, $user);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Un-Follow user failed', $e->getCode() ?: 500);
        }
    }
}
