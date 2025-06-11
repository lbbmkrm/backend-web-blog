<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagResource;
use App\Services\TagService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    private TagService $tagService;
    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    public function index(): JsonResponse
    {
        try {
            $tags = $this->tagService->getAllTags();
            $message = $tags->isEmpty() ? 'Belum ada tag yang tersedia.' : 'Data tag berhasil diambil.';
            return $this->successResponse(
                $message,
                TagResource::collection($tags)
            );
        } catch (Exception $e) {
            dd(['controller code' => $e->getCode()]);
            return $this->failedResponse($e);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $tag = $this->tagService->findTagById($id);
            return $this->successResponse(
                'Data tag berhasil diambil',
                new TagResource($tag)
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'tags' => 'required|array',
                'tags.*' => 'string|max:255|distinct|unique:tags,name',
            ]);

            $tags = $this->tagService->createTags($validated['tags']);
            return $this->successResponse(
                'Berhasil membuat tags',
                TagResource::collection($tags),
                201
            );
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->tagService->deleteTag($id);
            return $this->successResponse('Berhasil menghapus tag');
        } catch (Exception $e) {
            return $this->failedResponse($e);
        }
    }
}
