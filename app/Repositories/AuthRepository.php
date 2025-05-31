<?php

namespace App\Repositories;

use App\Models\Profile;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\VerifiedStudentNumber;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class AuthRepository
{
    protected User $user;
    protected VerifiedStudentNumber $verifiedStudent;
    protected Profile $profile;

    public function __construct(User $user, VerifiedStudentNumber $student, Profile $profile)
    {
        $this->user = $user;
        $this->verifiedStudent = $student;
        $this->profile = $profile;
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
        try {
            return $this->user->create($data);
        } catch (MassAssignmentException $e) {
            throw new Exception('Data yang dikirim tidak valid.', 422);
        } catch (QueryException $e) {
            throw new Exception('Terjadi kesalahan pada database saat register.', 500);
        } catch (Exception $e) {
            throw new Exception('Terjadi kesalahan saat register.', 500);
        }
    }

    public function createProfileUser(array $data): ?Profile
    {
        try {
            return $this->profile->create($data);
        } catch (MassAssignmentException $e) {
            throw new Exception('Data yang dikirim tidak valid.', 422);
        } catch (QueryException $e) {
            throw new Exception('Terjadi kesalahan pada database saat membuat data profile.', 500);
        } catch (Exception $e) {
            throw new Exception('Terjadi kesalahan saat membuat data profile.', 500);
        }
    }

    public function currentUser(): ?User
    {
        return Auth::guard('sanctum')->user();
    }

    public function getVerifiedStudent(string $id): ?VerifiedStudentNumber
    {
        try {
            return $this->verifiedStudent->where('student_id_number', $id)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new Exception('Mahasiswa tidak ditemukan', 404);
        }
    }

    public function existUsername(string $username): bool
    {
        return $this->user->where('username', $username)->exists();
    }

    public function existEmail(string $email): bool
    {
        return $this->user->where('email', $email)->exists();
    }

    public function existsStudent(int $studentNumber): bool
    {
        return $this->verifiedStudent->where('student_id_number', $studentNumber)->exists();
    }
}
