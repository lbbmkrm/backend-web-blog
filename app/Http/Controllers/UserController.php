<?php

namespace App\Http\Controllers;

use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UsersResource as AllUsersResource;
use App\Models\Like;
use Illuminate\Http\Request;
use App\Services\UserService;
use Exception;

class UserController extends Controller
{
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
    public function getAllUsers()
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

    public function getUser(int $userId)
    {

        try {
            $user = $this->userService->getUser($userId);
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



    public function follow(int $userId)
    {
        try {
            $this->userService->follow($userId);
            return response()->json([
                'message' => 'Success following'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }

    public function unFollow(int $userId)
    {
        try {
            $this->userService->unFollow($userId);
            return response()->json([
                'message' => 'Success un-following'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
