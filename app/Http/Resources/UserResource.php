<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => (string)$this->id,
            'fullName' => $this->fullName,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'role' => $this->role,
            'permissions' => $this->permissions,
            'status' => $this->status,
            'createdAt' => $this->createdAt,
        ];
    }
} 