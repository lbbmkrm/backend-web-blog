<?php

namespace App\Http\Controllers;

use App\Http\Resources\Blogs\BlogsResource;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private CategoryService $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(): JsonResponse
    {
        try {
            $categories = $this->categoryService->listCategories();
            $message = $categories->isEmpty() ? 'No categories found' : 'Categories retrieved successfully';
            return $this->successResponse($message, CategoryResource::collection($categories));
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $category = $this->categoryService->getCategoryDetail($id);
            return $this->successResponse(
                'Category retrieved successfully',
                new CategoryResource($category)
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function blogs(int $id): JsonResponse
    {
        try {
            $blogs = $this->categoryService->getCategoryByBlogs($id);
            $message = $blogs->isEmpty() ? "No blogs found for this category" : "Blogs retrieved successfully";
            return $this->successResponse(
                $message,
                BlogsResource::collection($blogs)
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }
}
