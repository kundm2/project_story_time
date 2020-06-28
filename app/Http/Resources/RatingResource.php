<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RatingResource extends JsonResource
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
                'rating' => $this->rating,
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
