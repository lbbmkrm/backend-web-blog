<?php

namespace App\Http\Resources\Blogs;

use App\Http\Resources\BlogImageResource;
use App\Http\Resources\CommentResource;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\User\UserSimpleResource;
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
            'author' => new UserSimpleResource($this->whenLoaded('user')),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'content' => $this->content,
            'description' => $this->description,
            'thumbnail' => $this->thumbnail,
            'slug' => $this->slug,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'comments' =>  CommentResource::collection(($this->whenLoaded('comments'))),
            'likesCount' => $this->likes()->count(),
            'isLiked' => $this->isLiked()
        ];
    }
}
