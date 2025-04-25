<?php

namespace App\Repositories;

use App\Models\Blog;

class BlogRepository
{
    protected $model;

    public function __construct(Blog $blog)
    {
        $this->model = $blog;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function find(int $id): ?Blog
    {
        return $this->model->findOrFail($id);
    }

    public function findWithRelations(int $id)
    {
        return $this->model->with(['category', 'user', 'comments'])->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(Blog $blog, array $data)
    {
        $blog->update($data);
        return $blog;
    }

    public function delete(Blog $blog): void
    {
        $blog->delete();
    }
}
