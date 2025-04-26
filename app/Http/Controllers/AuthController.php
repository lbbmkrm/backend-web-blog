<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\User\UserResource;
use App\Http\Requests\Auth\RegisterRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('authentication_token')->plainTextToken;

        return response()->json([
            'message' => 'Login Successful',
            'userId' => $user->id,
            'token' => $token
        ], 200);
    }


    public function register(RegisterRequest $request)
    {

        $data = $request->validated();
        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'bio' => $data['bio'],
                'created_at' => now()
            ]);

            DB::commit();

            $token = $user->createToken('authentication_token')->plainTextToken;

            return response()->json([
                'message' => 'Register Success',
                'token' => $token,
                'userId' => $user->id,
                'user' => new UserResource($user)
            ], 201);
        } catch (Exception $e) {

            DB::rollBack();

            return response()->json([
                'message' => 'User register failed.'
            ], 500);
        }
    }
}
