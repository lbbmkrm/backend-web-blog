<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryRepository
{
    protected Category $model;

    public function __construct(Category $category)
    {
        $this->model = $category;
    }

    public function getAll(): ?Collection
    {
        return $this->model->all();
    }

    public function getById(int $id): ?Category
    {
        return $this->model->where('id', $id)->first();
    }

    public function blogs(Category $category): ?Collection
    {
        return $category->blogs;
    }
}
