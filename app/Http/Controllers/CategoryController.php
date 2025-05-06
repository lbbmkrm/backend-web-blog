<?php

namespace App\Http\Controllers;

use App\Http\Resources\Blogs\BlogsResource;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        try {
            $categories = $this->categoryService->getAllCategories();
            return response()->json([
                'message' => 'Success',
                'categories' => CategoryResource::collection($categories)
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }

    public function show(int $id)
    {
        try {
            $category = $this->categoryService->getSingleCategories($id);
            return response()->json([
                'message' => 'success',
                'category' => new CategoryResource($category)
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function blogs(int $id)
    {
        try {
            $blogs = $this->categoryService->getCategoryBlogs($id);
            $message = $blogs->isEmpty() ? "no blogs" : "success";
            return response()->json([
                'message' => $message,
                'blogs' => BlogsResource::collection($blogs)
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
