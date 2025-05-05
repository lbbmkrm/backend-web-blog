<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserSimpleResource;
use App\Http\Resources\User\UsersResource as AllUsersResource;
use App\Models\Like;
use Illuminate\Http\Request;
use App\Services\UserService;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
    use AuthorizesRequests;
    private $userService;
    public function __construct(UserService $service)
    {
        $this->userService = $service;
    }
    public function likes()
    {
        $likes = Like::all();
        return response()->json([
            'data' => $likes->toArray()
        ]);
    }
    public function index()
    {
        try {
            $users = $this->userService->getAllUsers();
            return response()->json([
                'message' => 'Success get all users',
                'data' => AllUsersResource::collection($users)
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed get all users',
            ], $e->getCode() ?: 500);
        }
    }

    public function show(int $id)
    {

        try {
            $user = $this->userService->getUser($id);
            return response()->json([
                'message' => 'success',
                'data' => new UserResource($user)
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }

    public function update(int $id, UserUpdateRequest $request)
    {
        $requestValid = $request->validated();
        try {
            $user = $this->userService->getUser($id);
            $this->authorize('update', $user);
            $updatedUser = $this->userService->updateUser($user, $requestValid);
            return response()->json([
                'message' => 'updated user success',
                'user' => new UserResource($updatedUser)
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }



    public function follow(int $id)
    {
        try {
            $this->userService->follow($id);
            return response()->json([
                'message' => 'Success following'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }

    public function unFollow(int $id)
    {
        try {
            $this->userService->unFollow($id);
            return response()->json([
                'message' => 'Success un-following'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function followers(int $id)
    {
        try {
            $followers = $this->userService->getFollowers($id);
            $message = $followers->isEmpty()
                ? 'User has no followers.' : 'Followers retrieved successfully.';
            return response()->json([
                'message' => $message,
                'followers' => UserSimpleResource::collection($followers)
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function following(int $id)
    {
        try {
            $following = $this->userService->getFollowing($id);
            $message = $following->isEmpty()
                ? 'no following data' : 'success retrive following data';
            return response()->json([
                'message' => $message,
                'following' => UserSimpleResource::collection($following)
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }



    public function blogs(int $id)
    {
        try {
            $blogs = $this->userService->getUserBlog($id);
            $message = $blogs->isEmpty()
                ? "no user's blogs" : "Success retrive user's blog";
            return response()->json([
                'message' => $message,
                'blogs' => $blogs->toArray()
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
