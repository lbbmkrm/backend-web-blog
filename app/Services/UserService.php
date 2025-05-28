<?php

namespace App\Services;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Repositories\FollowRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    protected UserRepository $userRepo;
    protected FollowRepository $followRepo;
    public function __construct(UserRepository $userRepository, FollowRepository $followRepository)
    {
        $this->userRepo = $userRepository;
        $this->followRepo = $followRepository;
    }
    public function getAllUsers(): ?Collection
    {
        try {
            $users = $this->userRepo->getAll();
            return $users;
        } catch (Exception $e) {
            throw new Exception('failed retrieve users', 500);
        }
    }
    public function getUser(int $userId): User
    {
        $user = $this->userRepo->getUser($userId);
        if (!$user) {
            throw new Exception('User not found', 404);
        }
        return $user;
    }
    public function isSameUser(int $userId, int $currentUserId): bool
    {
        return $userId === $currentUserId;
    }

    public function updateUser(User $user, array $request): ?User
    {
        try {
            Gate::authorize('update', $user);
            $data = [
                'bio' => $request['bio'] ?: null,
                'avatar' => $request['avatar'] ?: null,
                'phone' => $request['phone'] ?: null
            ];
            if (isset($request['avatar'])) {

                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $path = $request['avatar']->store('avatars', 'public');
                $data['avatar'] = $path;
            }
            DB::beginTransaction();
            $updatedUser = $this->userRepo->update($user, $data);
            DB::commit();
            return $updatedUser;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    public function follow(int $userId)
    {
        $currentUser = Auth::guard('sanctum')->user();
        $user = $this->userRepo->getUser($userId);
        if ($this->isSameUser($user->id, $currentUser->id)) {
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

    public function unfollow(int $userId)
    {
        $user = $this->getUser($userId);
        $currentUser = Auth::guard('sanctum')->user();
        if ($this->isSameUser($userId, $currentUser->id)) {
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

    public function getFollowers(int $userId): ?Collection
    {
        $user = $this->getUser($userId);
        try {
            $followers = $this->userRepo->followers($user);
            return $followers;
        } catch (Exception $e) {
            throw new Exception('failed to retrive data', 500);
        }
    }

    public function getFollowing(int $userId): ?Collection
    {
        $user = $this->getUser($userId);
        try {
            $following = $this->userRepo->following($user);
            return $following;
        } catch (Exception $e) {
            throw new Exception('failed to retrive data', 500);
        }
    }


    public function getUserBlogs(int $id): ?Collection
    {
        try {
            $user = $this->getUser($id);
            $blogs = $this->userRepo->blogs($user);
            return $blogs;
        } catch (Exception $e) {
            throw new Exception("Failed retrive user's blog", 500);
        }
    }

    public function getUserLikedBlogs(int $userId): ?Collection
    {
        try {
            $user = $this->getUser($userId);
            $liked = $this->userRepo->likes($user);
            return $liked;
        } catch (Exception $e) {
            throw new Exception($e->getMessage() ?: "failed retrieve user's liked blog", $e->getCode() ?: 500);
        }
    }
}
