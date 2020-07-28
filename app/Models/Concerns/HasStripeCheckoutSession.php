<?php

namespace App\Models\Concerns;

use App\Models\StripeCheckoutSession;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

trait HasStripeCheckoutSession
{
    /**
     * Get the stripe checkout session that this model owns.
     *
     * @return MorphOne The morph one.
     */
    public function stripeCheckoutSession(): MorphOne
    {
        return $this->morphOne(StripeCheckoutSession::class, 'model');
    }


}
