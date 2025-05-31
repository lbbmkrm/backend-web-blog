<?php

namespace App\Services;

use App\Models\Bookmark;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repositories\BookmarkRepository;
use Illuminate\Database\Eloquent\Collection;

class BookmarkService
{
    protected BookmarkRepository $bookmarkRepo;

    public function __construct(BookmarkRepository $bookmarkRepository)
    {
        $this->bookmarkRepo = $bookmarkRepository;
    }

    public function getUser(): User
    {
        return Auth::guard('sanctum')->user();
    }

    public function getAll(): ?Collection
    {
        try {
            $user = $this->getUser();
            return $this->bookmarkRepo->getAllByUserId($user->id);
        } catch (Exception $e) {
            throw new Exception(
                $e->getMessage() ?: 'Gagal mengambil data bookmark.',
                $e->getCode() ?: 500
            );
        }
    }

    public function createBookmark(int $blogId): Bookmark
    {
        try {
            $user = $this->getUser();
            DB::beginTransaction();

            if ($this->bookmarkRepo->exist($user->id, $blogId)) {
                throw new Exception('Blog sudah ditandai sebagai bookmark.', 400);
            }

            $newBookmark = $this->bookmarkRepo->create([
                'user_id' => $user->id,
                'blog_id' => $blogId
            ]);

            DB::commit();
            return $newBookmark;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(
                $e->getMessage() ?: 'Gagal menambahkan bookmark.',
                $e->getCode() ?: 500
            );
        }
    }

    public function deleteBookmark(int $bookmarkId): bool
    {
        try {
            $bookmark = $this->bookmarkRepo->getById($bookmarkId);
            $user = $this->getUser();

            if ($bookmark->user_id !== $user->id) {
                throw new Exception('Anda tidak memiliki akses untuk menghapus bookmark ini.', 403);
            }

            return $this->bookmarkRepo->delete($bookmark);
        } catch (Exception $e) {
            throw new Exception(
                $e->getMessage() ?: 'Gagal menghapus bookmark.',
                $e->getCode() ?: 500
            );
        }
    }
}
