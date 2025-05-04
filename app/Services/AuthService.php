<?php

namespace App\Services;

use App\Repositories\AuthRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;

class AuthService
{
    protected $authRepo;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepo = $authRepository;
    }

    public function login(array $requestData)
    {
        try {
            $this->authRepo->login($requestData['email'], $requestData['password']);
            $currentUser = $this->authRepo->currentUser();
            $token = $currentUser->createToken('authentication_token')->plainTextToken;
            return ['user' => $currentUser, 'token' => $token];
        } catch (Exception $e) {
            throw new Exception('Invalid credentials!', 401);
        }
    }

    public function register(array $requestData)
    {
        try {
            DB::beginTransaction();
            $user = $this->authRepo->register($requestData);
            $token = $user->createToken('authentication_token')->plainTextToken;
            DB::commit();
            $newUser = ['user' => $user, 'token' => $token];
            return $newUser;
        } catch (Exception $e) {
            DB::rollBack();
            $message = null;
            $code = 0;
            if ($e->getCode() === 23000) {
                $message = 'Email already registered';
                $code = 422;
            }
            throw new Exception($message ?: 'Failed to register', $code ?:  500);
        }
    }

    public function logout()
    {
        try {
            $user = $this->authRepo->currentUser();
            if (!$user) {
                throw new Exception('No authenticated user found', 401);
            }
            /** @var PersonalAccessToken|null $token */
            $token = $user->currentAccessToken();
            if ($token) {
                $token->delete();
            }
        } catch (Exception $e) {
            throw new Exception('Failed to logout', $e->getCode() ?: 500);
        }
    }

    public function currentUser()
    {
        try {
            $user = $this->authRepo->currentUser();
            if (!$user) {
                throw new Exception('Current User not found', 404);
            }
            return $user;
        } catch (Exception $e) {
            throw new Exception($e->getMessage() ?: 'Failed', $e->getCode() ?: 500);
        }
    }
}
