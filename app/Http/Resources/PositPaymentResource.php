<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PositPaymentResource extends JsonResource
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
            'provider' => $this->provider,
            'type' => $this->type,
            'amount' => $this->amount,
            'stripe_checkout_session_id' => $this->whenLoaded('stripeCheckoutSession', function () {
                return $this->stripeCheckoutSession->id;
            }),
            'stripe_account_id' => $this->whenLoaded('stripeCheckoutSession', function () {
                return $this->stripeCheckoutSession->stripe_account_id;
            }),
        ];
    }
}
