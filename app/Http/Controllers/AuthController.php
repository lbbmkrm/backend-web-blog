<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\LoginResource;
use App\Http\Requests\Auth\RegisterRequest;

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
            $result = $this->authService->login($credentials);
            return response()->json([
                'message' => 'Login Successfully',
                'user' => new LoginResource($result['user']),
                'token' => $result['token']
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }


    public function register(RegisterRequest $request)
    {

        try {
            $data = $request->validated();
            $newUser = $this->authService->register($data);
            return response()->json([
                'message' => 'Success register',
                'userId' => $newUser['user']['id'],
                'token' => $newUser['token'],
                'user' => new LoginResource($newUser['user'])
            ], 201);
        } catch (Exception $e) {
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

    public function checkUsername(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255'
        ]);
        $username = $validated['username'];

        try {
            $checked = $this->authService->checkUsername($username);
            return response()->json([
                'exist' => $checked
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function checkEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email'
        ]);
        $email = $validated['email'];
        try {
            $exist = $this->authService->checkEmail($email);
            return response()->json([
                'exist' => $exist
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function checkStudentNumber(Request $request)
    {
        $validated = $request->validate([
            'student_id_number' => 'required|string'
        ]);
        $studentNumber = $validated['student_id_number'];
        try {
            $exist = $this->authService->checkStudentNumber($studentNumber);
            return response()->json([
                'exist' => $exist
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
