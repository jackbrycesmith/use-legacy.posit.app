<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

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
            'dashboard_name' => Arr::get($this->settings, 'dashboard.display_name'),
            'charges_enabled' => (bool) $this->charges_enabled,
            'payouts_enabled' => (bool) $this->payouts_enabled,
            'details_submitted' => (bool) $this->details_submitted,
            'has_card_payments_capability' => $this->hasCapability('card_payments'),
            'has_bacs_debit_payments_capability' => $this->hasCapability('bacs_debit_payments'),
        ];
    }
}
