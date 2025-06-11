<?php

namespace App\Services;

use Exception;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use App\Repositories\TagRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class TagService
{
    protected TagRepository $tagRepo;
    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepo = $tagRepository;
    }

    public function getAllTags(): Collection
    {
        try {
            return $this->tagRepo->getAll();
        } catch (Exception $e) {
            throw new Exception(
                $e->getMessage() ?: 'Gagal mengambil daftar tag.',
                $e->getCode() ?: 500
            );
        }
    }

    public function findTagById(int $tagId): Tag
    {
        try {
            return $this->tagRepo->getById($tagId);
        } catch (Exception $e) {
            throw new Exception(
                $e->getMessage() ?: 'Gagal mengambil tag',
                $e->getCode() ?: 500
            );
        }
    }

    public function createTags(array $tags): Collection
    {
        try {
            DB::beginTransaction();
            $user = Auth::guard('sanctum')->user();
            $createdTags = new Collection();

            foreach ($tags as $tagName) {
                $tag = $this->tagRepo->model->create([
                    'user_id' => $user->id,
                    'name' => $tagName
                ]);
                $createdTags->push($tag);
            }

            DB::commit();
            return $createdTags;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(
                $e->getMessage() ?: 'Gagal membuat tag',
                $e->getCode() ?: 500
            );
        }
    }

    public function deleteTag(int $tagId): void
    {
        try {
            $user = Auth::guard('sanctum')->user();
            $tag = $this->findTagById($tagId);
            if ($tag->user_id !== $user->id) {
                throw new Exception('Unauthorized', 403);
            }
            $this->tagRepo->delete($tag);
        } catch (Exception $e) {
            throw new Exception(
                $e->getMessage() ?: 'Gagal menghapus tag',
                $e->getCode() ?: 500
            );
        }
    }
}
