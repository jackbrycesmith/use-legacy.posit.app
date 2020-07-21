<?php

namespace App\Actions\Integrations\Stripe;

use App\Actions\Integrations\Stripe\UpdateStripeAccountDetails;
use App\Models\StripeAccount;
use Lorisleiva\Actions\Action;

class HandleStripeConnectFetchedUserCredentials extends Action
{
    /**
     * Execute the action and return a result.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->account instanceof StripeAccount) {
            UpdateStripeAccountDetails::dispatch([
                'account' => $this->account
            ]);
        }
    }
}
