<?php

namespace App\Repositories;

use App\Models\Tag;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Database\QueryException;

class TagRepository
{
    public Tag $model;

    public function __construct(Tag $tag)
    {
        $this->model = $tag;
    }

    public function getAll(): Collection
    {
        try {
            return $this->model->with('blogs')->get();
        } catch (RelationNotFoundException $e) {
            throw new Exception('Relasi yang diminta tidak ditemukan.', 500);
        } catch (QueryException $e) {
            throw new Exception('Terjadi kesalahan pada database saat mengambil daftar tag.', 500);
        } catch (Exception $e) {
            throw new Exception('Terjadi kesalahan saat mengambil data tag.', 500);
        }
    }

    public function getById(int $id): Tag
    {
        try {
            return $this->model->with('blogs')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new Exception('Tag dengan ID tersebut tidak ditemukan.', 404);
        } catch (QueryException $e) {
            throw new Exception('Terjadi kesalahan pada database saat mengambil data tag.', 500);
        } catch (Exception $e) {
            throw new Exception('Terjadi kesalahan saat mengambil data tag.', 500);
        }
    }

    public function getOrCreate(array $attributes, array $values = []): Tag
    {
        try {
            return $this->model->firstOrCreate($attributes, $values);
        } catch (MassAssignmentException $e) {
            throw new Exception('Data yang dikirim tidak valid. Periksa kembali input Anda.', 422);
        } catch (QueryException $e) {
            throw new Exception('Terjadi kesalahan pada database saat menyimpan tag.', 500);
        } catch (Exception $e) {
            throw new Exception('Terjadi kesalahan saat membuat tag.', 500);
        }
    }

    public function create(array $data): Tag
    {
        try {
            return $this->model->create($data);
        } catch (MassAssignmentException $e) {
            throw new Exception('Data yang dikirim tidak valid. Periksa kembali input Anda.', 422);
        } catch (QueryException $e) {
            throw new Exception('Terjadi kesalahan pada database saat menyimpan tag.', 500);
        } catch (Exception $e) {
            throw new Exception('Terjadi kesalahan saat membuat tag.', 500);
        }
    }


    public function delete(Tag $tag): void
    {
        try {
            $tag->delete();
        } catch (QueryException $e) {
            throw new Exception('Terjadi kesalahan pada database saat menghapus tag.', 500);
        } catch (Exception $e) {
            throw new Exception('Terjadi kesalahan saat menghapus tag.', 500);
        }
    }
}
