<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PostCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($item) {
            $owner = User::find($item->user_id);
            return [
                'id' => $item->id,
                'title' => $item->title,
                'body' => $item->body,
                'owner_fullname' => $owner->first_name . ' ' . $owner->last_name,
                'created_at' => $item->created_at,
                'updated_at'=> $item->updated_at
            ];
        })->toArray();
    }
}
