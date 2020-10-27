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
            'name' => $this->name,
            'status' => $this->status,
            'meta' => $this->meta,
            'creator' => new UserResource($this->whenLoaded('creator')),
            'value_amount' => $this->value_amount,
            'value_currency_code' => $this->value_currency_code,
            'content' => new ProposalContentResource($this->whenLoaded('proposalContent')),
            'deposit_payment' => new ProposalPaymentResource($this->whenLoaded('depositPayment')),
            'org' => new TeamResource($this->whenLoaded('team')),
            'users' => UserResource::collection($this->whenLoaded('users')),
            'recipient' => new TeamContactResource($this->whenLoaded('recipient')),
            'stripe_checkout_session_id' => $this->whenLoaded('stripeCheckoutSession', function () {
                return $this->stripeCheckoutSession->id;
            }),
            'stripe_account_id' => $this->whenLoaded('stripeCheckoutSession', function () {
                return $this->stripeCheckoutSession->stripe_account_id;
            }),
            'intro_video' => new VideoResource($this->whenLoaded('video')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
