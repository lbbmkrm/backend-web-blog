<?php

namespace App\Http\Resources\Blogs;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogsResource extends JsonResource
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
            'description' => $this->description,
            'slug' => $this->slug,
            'thumbnail' => $this->thumbnail,
            'createdAt' => $this->created_at->format('d-m-Y H:i'),
        ];
    }
}
