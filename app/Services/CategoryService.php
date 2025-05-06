<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Exception;
use Illuminate\Support\Collection;

class CategoryService
{
    protected $categoryRepo;
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepo = $categoryRepository;
    }

    public function getAllCategories(): ?Collection
    {
        try {
            $category = $this->categoryRepo->getAll();
            if ($category->isEmpty()) {
                throw new Exception('no category found', 200);
            }
            return $category;
        } catch (Exception $e) {
            throw new Exception('Failed to retrive category', 500);
        }
    }

    public function getSingleCategories(int $id)
    {
        try {
            $category = $this->getSingleCategories($id);
            if (!$category) {
                throw new Exception('no category', 404);
            }
            return $category;
        } catch (Exception $e) {
            throw new Exception('failed to get category', 500);
        }
    }

    public function getCategoryBlogs(int $id)
    {
        $categories = $this->getSingleCategories($id);
        try {
            $blogs = $this->categoryRepo->blogs($categories);
            return $blogs;
        } catch (Exception $e) {
            throw new Exception('failed to retrive blogs', 500);
        }
    }
}
