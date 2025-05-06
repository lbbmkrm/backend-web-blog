<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryRepository
{
    /**
     * @var \App\Models\Category
     */
    protected $model;

    public function __construct(Category $category)
    {
        $this->model = $category;
    }

    public function getAll(): ?Collection
    {
        return $this->model->all();
    }

    public function getSingle(int $id): ?Category
    {
        return $this->model->find($id);
    }

    public function blogs(Category $category)
    {
        return $category->blogs;
    }
}
