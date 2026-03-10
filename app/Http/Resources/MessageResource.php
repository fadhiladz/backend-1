<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'conversation_id' => $this->conversation_id,
            'sender'          => new UserResource($this->whenLoaded('sender')),
            'content'         => $this->content,
            'media_url'       => $this->media_url,
            'media_type'      => $this->media_type,
            'created_at'      => $this->created_at,
        ];
    }
}
