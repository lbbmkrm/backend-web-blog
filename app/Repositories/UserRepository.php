<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class UserRepository
{
    private $model;
    public function __construct(User $user)
    {
        $this->model = $user;
    }
    public function getAll(): Collection
    {
        return $this->model->all();
    }
    public function getUser(int $id): ?User
    {
        return $this->model->find($id);
    }
}
