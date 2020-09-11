<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
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
            'uuid' => $this->uuid,
            'mime_type' => $this->mime_type,
            'size' => $this->size,
            'url' => $this->url, // TODO
            'hls_url' => null, // TODO
            'downloadable_at' => $this->downloadable_at,
            'streamable_at' => $this->streamable_at,
        ];
    }
}
