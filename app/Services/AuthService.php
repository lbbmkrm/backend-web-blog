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
            $credential = $requestData['username'] ?? $requestData['email'];

            if (!$this->authRepo->login($credential, $requestData['password'])) {
                throw new Exception('Invalid credentials!', 401);
            }

            $currentUser = $this->authRepo->currentUser();
            if (!$currentUser) {
                throw new Exception('User not found!', 401);
            }
            $token = $currentUser->createToken('authentication_token')->plainTextToken;

            return ['user' => $currentUser, 'token' => $token];
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 401);
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
            throw new Exception($e->getMessage() ?: 'Register failed', $e->getCode() ?:  500);
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

    public function checkUsername(string $username)
    {
        try {
            $existed = $this->authRepo->existUsername($username);
            return $existed;
        } catch (Exception $e) {
            throw new Exception('failed check username', 500);
        }
    }

    public function checkEmail(string $email)
    {
        try {
            $existed = $this->authRepo->existEmail($email);
            return $existed;
        } catch (Exception $e) {
            throw new Exception('failed check email', 500);
        }
    }

    public function checkStudentNumber(int $studentNumber)
    {
        try {
            $existed = $this->authRepo->verifiedStudent($studentNumber);
            return $existed;
        } catch (Exception $e) {
            throw new Exception('failed check student number', 500);
        }
    }
}
