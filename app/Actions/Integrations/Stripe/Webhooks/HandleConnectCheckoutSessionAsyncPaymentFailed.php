<?php

namespace App\Actions\Integrations\Stripe\Webhooks;

use App\Actions\Integrations\Stripe\UpdateStripeCheckoutSessionFromWebhook;
use CloudCreativity\LaravelStripe\Webhooks\ConnectWebhook;
use Lorisleiva\Actions\Action;

class HandleConnectCheckoutSessionAsyncPaymentFailed extends Action
{
    public function getAttributesFromEvent(ConnectWebhook $event)
    {
        return [
            'webhook' => $event,
        ];
    }

    /**
     * Execute the action and return a result.
     *
     * @return mixed
     */
    public function handle()
    {
        logger('HandleConnectCheckoutSessionAsyncPaymentFailed...');
        $stripeCheckoutSession = UpdateStripeCheckoutSessionFromWebhook::run($this->webhook);
    }
}
