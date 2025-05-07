<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->user_id,
            'name' => $this->name,
            'bio' => $this->bio,
            'avatar' => $this->avatar,
            'student_number' => $this->student_number,
            'university' => $this->university,
            'faculty' => $this->faculty,
            'study_program' => $this->study_program,
            'batch' => $this->batch,
            'phone' => $this->phone,
            'created' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
