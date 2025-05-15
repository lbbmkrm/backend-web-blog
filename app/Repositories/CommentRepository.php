<?php

namespace App\Repositories;

use App\Models\Comment;
use Exception;

class CommentRepository
{
    protected $model;
    public function __construct(Comment $comment)
    {
        $this->model = $comment;
    }
    public function create(array $data): Comment
    {
        return $this->model->create($data);
    }

    public function find(int $id): Comment|null
    {
        return $this->model->findOrFail($id);
    }

    public function delete(Comment $comment): void
    {
        $comment->delete();
    }
    public function findByBlogAndUser(int $blogId, int $userId): ?Comment
    {
        $comment =  $this->model->where('blog_id', '=', $blogId)
            ->where('user_id', '=', $userId)->first();
        return $comment;
    }
}
