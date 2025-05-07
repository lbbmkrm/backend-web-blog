<?php

namespace App\Repositories;

use App\Models\Profile;

class ProfileRepository
{
    protected $model;
    public function __construct(Profile $profile)
    {
        $this->model = $profile;
    }

    public function functionName() {}

    public function updateProfile() {}
}
