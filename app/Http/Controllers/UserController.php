<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\User\UserResource;

class UserController extends Controller
{
    public function getUsers()
    {
        $users = User::all();

        return UserResource::collection($users);
    }

    public function likes()
    {
        $likes = Like::all();
        return response()->json([
            'data' => $likes->toArray()
        ]);
    }
}
