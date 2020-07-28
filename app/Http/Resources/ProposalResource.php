<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProposalResource extends JsonResource
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
            'meta' => $this->meta,
            'content' => new ProposalContentResource($this->whenLoaded('proposalContent')),
            'org' => new OrganisationResource($this->whenLoaded('organisation')),
            'users' => UserResource::collection($this->whenLoaded('users')),
            'stripe_checkout_session_id' => $this->whenLoaded('stripeCheckoutSession', function () {
                return $this->stripeCheckoutSession->id;
            }),
            'stripe_account_id' => $this->whenLoaded('stripeCheckoutSession', function () {
                return $this->stripeCheckoutSession->stripe_account_id;
            }),
        ];
    }
}
