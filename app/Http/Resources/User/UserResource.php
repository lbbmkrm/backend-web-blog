<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'username' => $this->username,
            'email' => $this->email,
            'detail' => new ProfileResource($this->profile),
            'followers' => FollowerResource::collection($this->whenLoaded('followers')),
            'following' => FollowerResource::collection($this->whenLoaded('following')),
        ];
    }
}
