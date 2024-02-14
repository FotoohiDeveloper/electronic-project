<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $owner = User::find($this->user_id);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'owner_fullname' => $owner->first_name . ' ' . $owner->last_name,
            'created_at' => $this->created_at,
            'updated_at'=> $this->updated_at
        ];
    }
}
