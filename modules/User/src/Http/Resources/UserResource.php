<?php

namespace Modules\User\src\Http\Resources;

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
            'name' => $this->name,
            'email' => $this->email,
            'group_id' => $this->group_id,
            'group_name' => $this->group_id == 1 ? 'Admin' : 'User',
            'status' => $this->status,
            'status_name' => $this->status == 1 ? 'Active' : 'Inactive',
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
