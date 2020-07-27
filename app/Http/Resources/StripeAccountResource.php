<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StripeAccountResource extends JsonResource
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
            'default_currency' => $this->default_currency,
            'email' => $this->email,
            'country' => $this->country,
        ];
    }
}
