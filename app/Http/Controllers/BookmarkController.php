<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookmarkStoreRequest;
use Exception;
use Illuminate\Http\Request;
use App\Services\BookmarkService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\BookmarkResource;

class BookmarkController extends Controller
{
    private BookmarkService $bookmarkService;

    public function __construct(BookmarkService $bookmarkService)
    {
        $this->bookmarkService = $bookmarkService;
    }

    public function index(): JsonResponse
    {
        try {
            $bookmarks = $this->bookmarkService->getAll();
            $message = $bookmarks->isEmpty()
                ? 'Pengguna belum memiliki bookmark.'
                : 'Data bookmark berhasil diambil.';

            return $this->successResponse(
                $message,
                BookmarkResource::collection($bookmarks)
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function store(BookmarkStoreRequest $request): JsonResponse
    {
        try {
            $validatedRequest = $request->validated();
            $bookmark = $this->bookmarkService->createBookmark($validatedRequest['blog_id']);

            return $this->successResponse(
                'Bookmark berhasil ditambahkan.',
                new BookmarkResource($bookmark),
                201
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->bookmarkService->deleteBookmark($id);
            return response()->json([
                'success' => true,
                'message' => 'Bookmark berhasil dihapus.'
            ]);
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }
}
