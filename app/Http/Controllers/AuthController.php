<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

        $imagePath = '';
        if ($request->hasFile('img')) {
            $imagePath = $request->file('img')->store('profile_image', 'public');
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'img' => $imagePath,
            'bio' => $data['bio']
        ]);

        $token = $user->createToken('authentication_token')->plainTextToken;

        return response()->json([
            'message' => 'Register Success',
            'token' => $token,
            'userId' => $user->id,
            'user' => new UserResource($user)
        ]);
    }
}
