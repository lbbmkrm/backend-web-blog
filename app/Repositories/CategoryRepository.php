<?php

namespace App\Repositories;

use Exception;
use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new Exception('Category tidak ditemukan', 404);
        }
    }

    public function blogs(Category $category): ?Collection
    {
        return $category->blogs->with(['user', 'category'])->get();
    }
}
