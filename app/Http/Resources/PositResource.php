<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PositResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'name' => $this->name,
            'type' => $this->type,
            'state' => $this->state,
            'meta' => $this->meta,
            'content' => $this->content,
            'creator' => new UserResource($this->whenLoaded('creator')),
            'value_amount' => $this->value_amount,
            'value_currency_code' => $this->value_currency_code,
            'deposit_payment' => new PositPaymentResource($this->whenLoaded('depositPayment')),
            'org' => new TeamResource($this->whenLoaded('team')),
            'users' => UserResource::collection($this->whenLoaded('users')),
            'recipient' => new TeamContactResource($this->whenLoaded('recipient')),
            'intro_video' => new VideoResource($this->whenLoaded('video')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
