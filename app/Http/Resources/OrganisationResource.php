<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganisationResource extends JsonResource
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
            'users' => UserResource::collection($this->whenLoaded('users')),
            'proposals' => ProposalResource::collection($this->whenLoaded('proposals')),
            'contacts' => OrgContactResource::collection($this->whenLoaded('contacts')),
            'stripeAccount' => new StripeAccountResource($this->whenLoaded('stripeAccount')),
            'created_at' => $this->created_at,
        ];
    }
}
