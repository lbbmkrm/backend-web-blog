<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Repositories\BlogRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BlogService
{
    protected $blogRepo;

    public function __construct(BlogRepository $blog)
    {
        $this->blogRepo = $blog;
    }

    public function getAllBlogs()
    {
        return $this->blogRepo->getAll();
    }

    public function getSingleBlog(int $id)
    {
        $blog = $this->blogRepo->findWithRelations($id);
        if (!$blog) {
            return new Exception('Blog not found', 404);
        }
        return $blog;
    }

    public function createBlog(array $data, $imgPath = null)
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();
            $slug = Str::slug($data['title']);

            $blogData = [
                'user_id' => $user->id,
                'category_id' => $data['category_id'],
                'title' => $data['title'],
                'content' => $data['content'],
                'description' => $data['description'],
                'slug' => $slug,
                'thumbnail' => $imgPath ? $imgPath->store('thumbnails ', 'public') : '',
                'created_at' => now()
            ];

            $blog = $this->blogRepo->create($blogData);
            DB::commit();
            return $blog;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Blog failed to create', 500);
        }
    }

    public function updateBlog(int $id, array $data, $imgPath = null)
    {
        $blog = $this->blogRepo->find($id);
        if (!$blog) {
            throw new Exception('Blog not found', 404);
        }
        if ($imgPath && $blog->thumbnail) {
            Storage::disk('public')->delete($blog->thumbnail);
            $data['thumbnail'] = $imgPath->store('thumbnails', 'public');
        }

        $blogData = [
            'category_id' => $data['category_id'],
            'title' => $data['title'],
            'content' => $data['content'],
            'description' => $data['description'],
            'thumbnail' => $imgPath ?? $blog->thumbnail,
            'updated_at' => now()
        ];
        return $this->blogRepo->update($blog, $blogData);
    }

    public function deleteBlog(int $blogId)
    {
        $blog = $this->blogRepo->find($blogId);
        if (!$blog) {
            throw new Exception('Blog not found', 404);
        }

        try {
            if ($blog->thumbnail) {
                Storage::disk('public')->delete($blog->thumbnail);
            }
            $this->blogRepo->delete($blog);
        } catch (Exception $e) {
            throw new Exception('Blog failed to delete', 500);
        }
    }
}
