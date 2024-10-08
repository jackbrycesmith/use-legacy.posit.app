<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PositContentResource extends JsonResource
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
            'id' => $this->id,
            'content' => $this->content,
            'updated_at' => $this->updated_at,
        ];
    }
}
