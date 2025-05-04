<?php

namespace App\Repositories;

use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class AuthRepository
{
    private $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function login(string $email, string $password): bool
    {
        return Auth::attempt(['email' => $email, 'password' => $password]);
    }

    public function register(array $data): User
    {
        try {
            $newUser = $this->model->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'bio' => $data['bio']
            ]);
        } catch (QueryException $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }

        return $newUser;
    }

    public function currentUser(): ?User
    {
        return Auth::user();
    }
}
