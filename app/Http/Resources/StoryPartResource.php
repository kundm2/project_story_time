<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoryPartResource extends JsonResource
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
                'content' => $this->content,
                'is_image' => $this->is_image
            ],
            'links' => [
                'self' => $this->path(),
                'self_full' => $this->full_path()
            ]
        ];
    }
}
