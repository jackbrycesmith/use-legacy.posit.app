<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class TeamContactResource extends JsonResource
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
            'name' => Arr::get($this->meta, 'name'),
            'access_code' => $this->access_code, // TODO make sure this is private where necessary
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
