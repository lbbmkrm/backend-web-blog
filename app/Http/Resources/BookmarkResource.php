<?php

namespace App\Http\Resources;

use App\Http\Resources\Blogs\BlogSimpleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookmarkResource extends JsonResource
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
            'userId' => $this->user_id,
            'blogId' => $this->blog_id,
            'createdAt' => $this->created_at,
            'blog' => new BlogSimpleResource($this->whenLoaded('blog'))
        ];
    }
}
