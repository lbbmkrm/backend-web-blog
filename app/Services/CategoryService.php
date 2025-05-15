<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Exception;
use Illuminate\Support\Collection;

class CategoryService
{
    protected CategoryRepository $categoryRepo;
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepo = $categoryRepository;
    }

    public function listCategories(): ?Collection
    {
        try {
            $category = $this->categoryRepo->getAll();
            return $category;
        } catch (Exception $e) {
            throw new Exception($e->getMessage() ?: 'Failed to retrieve categories', $e->getCode() ?: 500);
        }
    }

    public function getCategoryDetail(int $id): ?Category
    {
        try {
            $category = $this->categoryRepo->getById($id);
            if (!$category) {
                throw new Exception('Category not found', 404);
            }
            return $category;
        } catch (Exception $e) {
            throw new Exception($e->getMessage() ?: 'failed to get category', $e->getCode() ?: 500);
        }
    }

    public function getCategoryByBlogs(int $id): ?Collection
    {
        $categories = $this->getCategoryDetail($id);
        try {
            $blogs = $this->categoryRepo->blogs($categories);
            return $blogs;
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve blogs for category', 500);
        }
    }
}
