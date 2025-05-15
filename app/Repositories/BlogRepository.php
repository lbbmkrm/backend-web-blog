<?php

namespace App\Repositories;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Collection;

class BlogRepository
{
    protected Blog $model;

    public function __construct(Blog $blog)
    {
        $this->model = $blog;
    }

    public function getAll(): ?Collection
    {
        return $this->model->with(['user', 'category'])
            ->orderBy('created_at', 'desc')->get();
    }

    public function getById(int $id, $relation = []): ?Blog
    {
        return $this->model->with($relation)->where('id', $id)->first();
    }

    public function create(array $data): ?Blog
    {
        return $this->model->create($data);
    }

    public function update(Blog $blog, array $data): ?Blog
    {
        $blog->update($data);
        return $blog;
    }

    public function delete(Blog $blog): void
    {
        $blog->delete();
    }
}
