<?php

namespace App\Repositories;

use App\Models\Bookmark;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Database\QueryException;

class BookmarkRepository
{
    protected Bookmark $model;

    public function __construct(Bookmark $bookmark)
    {
        $this->model = $bookmark;
    }

    public function getById(int $id): ?Bookmark
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new Exception('Bookmark not found', 404);
        } catch (Exception $e) {
            throw new Exception('Failed to get bookmark', 500);
        }
    }
    public function getAllByUserId(int $userId): Collection
    {
        try {
            return $this->model->with(['blog'])->where('user_id', $userId)->get();
        } catch (RelationNotFoundException $e) {
            throw new Exception('Failed to retrieve data due to a configuration error.', 500);
        } catch (QueryException $e) {
            throw new Exception('Failed to retrieve data due to a server error.', 500);
        } catch (Exception $e) {
            throw new Exception('Unable to retrieve bookmarks.', 500);
        }
    }

    public function create(array $data): Bookmark
    {
        try {
            return $this->model->create($data);
        } catch (MassAssignmentException $e) {
            throw new Exception('Invalid input. Some data could not be processed.', 422);
        } catch (QueryException $e) {
            throw new Exception('Failed to create data due to a server error.', 500);
        } catch (Exception $e) {
            throw new Exception('Unable to create bookmark.', 500);
        }
    }

    public function delete(Bookmark $bookmark): ?bool
    {
        try {
            return $bookmark->delete();
        } catch (Exception $e) {
            throw new Exception('Failed to delete bookmark', 505);
        }
    }

    public function exist(int $userId, int $blogId): bool
    {
        return $this->model->where('user_id', $userId)
            ->where('blog_id', $blogId)->exists();
    }
}
