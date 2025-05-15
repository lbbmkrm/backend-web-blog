<?php

namespace App\Http\Resources;

use App\Http\Resources\Blogs\BlogResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'user' => $this->user->username,
            'content' => $this->content,
            'createdAt' => $this->created_at->format('d-m-Y H:i'),
        ];
    }
}
