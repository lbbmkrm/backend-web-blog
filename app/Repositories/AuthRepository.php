<?php

namespace App\Repositories;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\VerifiedStudentNumber;
use Illuminate\Database\QueryException;

class AuthRepository
{
    private $model;
    private $verifiedStudent;

    public function __construct(User $user, VerifiedStudentNumber $student)
    {
        $this->model = $user;
        $this->verifiedStudent = $student;
    }

    public function login(string $usernameOrEmail, string $password): bool
    {
        return Auth::attempt([
            filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL) ? 'email' : 'username' => $usernameOrEmail,
            'password' => $password
        ]);
    }

    public function register(array $data): User
    {
        if ($this->model->where('username', $data['username'])->exists()) {
            throw new Exception('username already exists', 409);
        }
        if ($this->model->where('email', $data['email'])->exists()) {
            throw new Exception('email already exists', 409);
        }
        try {
            DB::beginTransaction();
            $newUser = $this->model->create([
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
            ]);

            $existStudent = VerifiedStudentNumber::where('student_id_number', $data['student_number'])->first();
            if (!$existStudent) {
                throw new Exception('Student not found', 404);
            }
            $newUser->profile()->create([
                'user_id' => $newUser->id,
                'student_number' => $data['student_number'],
                'name' => $existStudent->name,
                'university' => $existStudent->university,
                'faculty' => $existStudent->faculty,
                'study_program' => $existStudent->study_program,
                'batch' => $existStudent->batch
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage(), $e->getCode());
        }
        return $newUser;
    }

    public function currentUser(): ?User
    {
        return Auth::guard('sanctum')->user();
    }

    public function existUsername(string $username): bool
    {
        return $this->model->where('username', $username)->exists();
    }

    public function existEmail(string $email): bool
    {
        return $this->model->where('email', $email)->exists();
    }

    public function verifiedStudent(int $studentNumber): bool
    {
        return $this->verifiedStudent->where('student_id_number', $studentNumber)->exists();
    }
}
