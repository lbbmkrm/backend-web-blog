<?php

namespace App\Http\Resources\Blogs;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => new UserResource($this->whenLoaded('user')),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'content' => $this->content,
            'description' => $this->description,
            'thumbnail' => $this->thumbnail,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
