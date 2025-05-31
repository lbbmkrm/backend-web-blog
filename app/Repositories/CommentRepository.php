<?php

namespace App\Repositories;

use Exception;
use App\Models\Comment;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CommentRepository
{
    protected $model;
    public function __construct(Comment $comment)
    {
        $this->model = $comment;
    }
    public function create(array $data): Comment
    {
        try {
            return $this->model->create($data);
        } catch (MassAssignmentException $e) {
            throw new Exception('Data yang dikirim tidak valid.', 422);
        } catch (QueryException $e) {
            throw new Exception('Terjadi kesalahan pada database saat membuat komentar.', 500);
        } catch (Exception $e) {
            throw new Exception('Terjadi kesalahan saat membuat komentar.', 500);
        }
    }

    public function find(int $id): Comment
    {
        try {
            return $this->model->with(['user'])->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new Exception('Komentar tidak ditemukan', 404);
        }
    }

    public function delete(Comment $comment): void
    {
        try {
            $comment->delete();
        } catch (QueryException $e) {
            throw new Exception('Terjadi kesalahan pada database saat menghapus komentar.', 500);
        } catch (Exception $e) {
            throw new Exception('Terjadi kesalahan saat menghapus komentar.', 500);
        }
    }
}
