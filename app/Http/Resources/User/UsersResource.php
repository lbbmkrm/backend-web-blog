<?php

namespace App\Http\Resources\User;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
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
            'name' => $this->name,
            'img_path' => $this->img,
            'createdAt' => $this->created_at->format('d-m-Y H:i'),
            'likedBlog' => Like::where('user_id', $this->id)->get('blog_id') //melanggar prinsip repository pattern
        ];
    }
}
