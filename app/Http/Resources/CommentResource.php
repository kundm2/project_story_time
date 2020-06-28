<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => [
                'id' => $this->id,
                'comment' => $this->comment,
                'story_id' => $this->story_id,
                'user_id' => $this->user_id
            ],
            'links' => [
                'self' => $this->path(),
                'self_full' => $this->full_path()
            ]
        ];
    }
}
