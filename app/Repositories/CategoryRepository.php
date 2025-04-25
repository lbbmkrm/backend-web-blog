<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    protected $category;

    public function __construct(Category $model)
    {
        $this->category = $model;
    }

    public function getAll()
    {
        return $this->category->all();
    }
}
