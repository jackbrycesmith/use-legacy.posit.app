<?php

namespace App\Actions\Integrations\Stripe;

use App\Events\StripeAccountDetailsUpdated;
use App\Models\StripeAccount;
use Illuminate\Contracts\Queue\ShouldQueue;
use Lorisleiva\Actions\Action;

class UpdateStripeAccountDetails extends Action implements ShouldQueue
{
    protected $getAttributesFromConstructor = ['account'];

    /**
     * Execute the action and return a result.
     *
     * @return mixed
     */
    public function handle(StripeAccount $account)
    {
        $account->updateFromStripeApi();

        event(new StripeAccountDetailsUpdated($account));
    }

    // TODO stripe rate limit job middleware?
}
