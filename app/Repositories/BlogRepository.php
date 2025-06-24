<?php

namespace App\Repositories;

use Exception;
use App\Models\Blog;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BlogRepository
{
    public Blog $model;

    public function __construct(Blog $blog)
    {
        $this->model = $blog;
    }

    public function getAll(): ?Collection
    {
        return $this->model->with(['user', 'category', 'tags'])
            ->orderBy('created_at', 'desc')->get();
    }

    public function getById(int $id, $relation = ['user', 'category', 'tags']): ?Blog
    {
        try {
            return $this->model->with($relation)->where('id', $id)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new Exception('Blog tidak ditemukan', 404);
        }
    }

    public function create(array $data): ?Blog
    {
        try {
            return $this->model->create($data);
        } catch (MassAssignmentException $e) {
            throw new Exception('Data yang dikirim tidak valid.', 422);
        } catch (QueryException $e) {
            throw new Exception('Terjadi kesalahan pada database saat membuat blog.', 500);
        } catch (Exception $e) {
            throw new Exception('Terjadi kesalahan saat membuat blog.', 500);
        }
    }

    public function update(Blog $blog, array $data): ?Blog
    {
        try {
            $blog->update($data);
            return $blog;
        } catch (MassAssignmentException $e) {
            throw new Exception($e->getMessage(), 422);
        } catch (QueryException $e) {
            throw new Exception('Terjadi kesalahan pada database saat memperbarui blog.', 500);
        } catch (Exception $e) {
            throw new Exception('Terjadi kesalahan saat memperbarui blog.', 500);
        }
    }

    public function delete(Blog $blog): void
    {
        try {
            $blog->delete();
        } catch (QueryException $e) {
            throw new Exception('Terjadi kesalahan pada database saat menghapus blog.', 500);
        } catch (Exception $e) {
            throw new Exception('Terjadi kesalahan saat menghapus blog.', 500);
        }
    }
}
