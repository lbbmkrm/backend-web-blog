<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\User\UserResource;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;

class AuthController extends Controller
{
    private $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        try {
            $user = $this->authService->login($credentials);
            return response()->json([
                'message' => 'Login Successfully',
                'user' => new UserResource($user['user']),
                'token' => $user['token']
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }


    public function register(RegisterRequest $request)
    {

        $data = $request->validated();
        try {
            DB::beginTransaction();
            $newUser = $this->authService->register($data);
            DB::commit();
            return response()->json([
                'message' => 'Success register',
                'userId' => $newUser['user']['id'],
                'token' => $newUser['token'],
                'user' => new UserResource($newUser['user'])
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function logout()
    {
        try {
            $this->authService->logout();
            return response()->json([
                'message' => 'Success logout'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function me()
    {
        try {
            $user = $this->authService->currentUser();
            return response()->json([
                'user' => new UserResource($user)
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
