<?php

namespace App\Services;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Repositories\FollowRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;

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

    public function updateUser(User $user, array $request)
    {
        try {
            if ($request['avatar']) {
            }
            $data = [
                'bio' => $request['bio'] ?: null,
                'avatar' => $request['avatar'] ?: null,
                'phone' => $request['phone'] ?: null
            ];
            if (isset($request['avatar']) && $request['avatar'] instanceof \Illuminate\Http\UploadedFile) {

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


    public function getUserBlog(int $id)
    {
        $user = $this->getUser($id);
        try {
            $blogs = $this->userRepo->blogs($user);
            return $blogs;
        } catch (Exception $e) {
            throw new Exception("Failed retrive user's blog", 500);
        }
    }
}
