<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
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
            'meta' => $this->meta,
            'owner' => new UserResource($this->whenLoaded('owner')),
            'logo_url' => $this->whenLoaded('media', function () {
                return $this->logo_url;
            }),
            'in_app_credit_balance' => $this->inAppCreditBalance(),
            'users' => UserResource::collection($this->whenLoaded('users')),
            'posits' => PositResource::collection($this->whenLoaded('posits')),
            'contacts' => TeamContactResource::collection($this->whenLoaded('contacts')),
            'stripeAccount' => new StripeAccountResource($this->whenLoaded('stripeAccount')),
            'published_posits_count' => $this->published_posits_count,
            'created_at' => $this->created_at,
        ];
    }
}
