<?php

namespace App\Http\Resources\Blogs;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $likeby = Like::where('blog_id', $this->id)->get('user_id');
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'slug' => $this->slug,
            'thumbnail' => $this->thumbnail,
            'createdAt' => $this->created_at->format('d-m-Y H:i'),
            'author' => new UserSimpleResource($this->whenLoaded('user')),
            'likeCount' => $this->likes->count(),
            'likesBy' => $likeby //melanggar prinsip repository pattern
        ];
    }
}
