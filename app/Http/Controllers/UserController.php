<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserSimpleResource;
use App\Http\Resources\User\UsersResource as AllUsersResource;
use Illuminate\Http\Request;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    private UserService $userService;
    public function __construct(UserService $service)
    {
        $this->userService = $service;
    }
    public function index(): JsonResponse
    {
        try {
            $users = $this->userService->getAllUsers();
            return $this->successResponse(
                'users retrieved successfully',
                AllUsersResource::collection($users)
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $user = $this->userService->getUser($id);
            return $this->successResponse(
                'User retrieved successfully',
                new UserResource($user)
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function update(int $id, UserUpdateRequest $request): JsonResponse
    {
        $requestValid = $request->validated();
        try {
            $user = $this->userService->getUser($id);
            $updatedUser = $this->userService->updateUser($user, $requestValid);
            return $this->successResponse(
                'User updated successfully',
                new UserResource($updatedUser)
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function follow(int $id): JsonResponse
    {
        try {
            $this->userService->follow($id);
            return $this->successResponse('Successfully followed user');
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function unfollow(int $id): JsonResponse
    {
        try {
            $this->userService->unFollow($id);
            return $this->successResponse('Successfully unfollowed user');
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function followers(int $id): JsonResponse
    {
        try {
            $followers = $this->userService->getFollowers($id);
            $message = $followers->isEmpty()
                ? 'User has no followers.' : 'Followers retrieved successfully.';
            return $this->successResponse($message, UserSimpleResource::collection($followers));
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function following(int $id): JsonResponse
    {
        try {
            $following = $this->userService->getFollowing($id);
            $message = $following->isEmpty()
                ? 'No following data available' : 'Successfully retrieved following data';
            return $this->successResponse($message, UserSimpleResource::collection($following));
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }



    public function indexBlogsByUser(int $id): JsonResponse
    {
        try {
            $blogs = $this->userService->getUserBlogs($id);
            $message = $blogs->isEmpty()
                ? "No blogs found for this user" : "Successfully retrieved user's blogs";
            return $this->successResponse($message, $blogs->toArray());
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function likes(int $id): JsonResponse
    {
        try {
            $liked = $this->userService->getUserLikedBlogs($id);
            return $this->successResponse("Successfully retrieved user's liked blogs", $liked->toArray());
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }
}
