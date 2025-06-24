<?php

namespace App\Http\Resources\Blogs;

use App\Models\Like;
use Illuminate\Http\Request;
use App\Http\Resources\TagResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CategorySimpleResource;
use App\Http\Resources\User\UserSimpleResource;
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
            'excerpt' => $this->excerpt,
            'slug' => $this->slug,
            'thumbnail' => $this->thumbnail,
            'createdAt' => $this->created_at->format('d-m-Y H:i'),
            'author' => new UserSimpleResource($this->whenLoaded('user')),
            'category' => new CategorySimpleResource($this->whenLoaded('category')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'likeCount' => $this->likes->count(),
            'isLiked' => $this->isLiked()
        ];
    }
}
