<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\AuthRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;

class AuthService
{
    protected AuthRepository $authRepo;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepo = $authRepository;
    }

    public function login(array $requestData): array
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

    public function register(array $requestData): array
    {
        try {
            if ($this->authRepo->existUsername($requestData['username'])) {
                throw new Exception('username sudah terdaftar', 409);
            }
            if ($this->authRepo->existEmail($requestData['email'])) {
                throw new Exception('email sudah terdaftar', 409);
            }
            $data = [
                'username' => $requestData['username'],
                'email' => $requestData['email'],
                'password' => bcrypt($requestData['password']),
            ];
            DB::beginTransaction();
            $user = $this->authRepo->register($data);

            $student = $this->authRepo->getVerifiedStudent($requestData['student_number']);
            $profileData = [
                'user_id' => $user->id,
                'name' => $student->name,
                'student_number' => $student->student_id_number,
                'university' => $student->university,
                'faculty' => $student->faculty,
                'study_program' => $student->study_program,
                'batch' => $student->batch,
            ];
            $this->authRepo->createProfileUser($profileData);
            $token = $user->createToken('authentication_token')->plainTextToken;
            DB::commit();
            return ['user' => $user, 'token' => $token];
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage() ?: 'Gagal registrasi', $e->getCode() ?:  500);
        }
    }

    public function logout(): void
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

    public function currentUser(): User
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

    public function checkUsername(string $username): bool
    {
        try {
            $existed = $this->authRepo->existUsername($username);
            return $existed;
        } catch (Exception $e) {
            throw new Exception('failed check username', 500);
        }
    }

    public function checkEmail(string $email): bool
    {
        try {
            $existed = $this->authRepo->existEmail($email);
            return $existed;
        } catch (Exception $e) {
            throw new Exception('failed check email', 500);
        }
    }

    public function checkStudentNumber(string $studentNumber): bool
    {
        try {
            $existed = $this->authRepo->existsStudent($studentNumber);
            return $existed;
        } catch (Exception $e) {
            throw new Exception('failed check student number', 500);
        }
    }
}
