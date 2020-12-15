<?php

namespace App\Actions\Integrations\Stripe\Webhooks;

use App\Actions\Integrations\Stripe\UpdateStripeCheckoutSessionFromWebhook;
use App\Models\PositPayment;
use App\Models\States\PositPayment\Paid;
use CloudCreativity\LaravelStripe\Webhooks\ConnectWebhook;
use Lorisleiva\Actions\Action;

class HandleConnectCheckoutSessionAsyncPaymentSucceeded extends Action
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
        $stripeCheckoutSession = UpdateStripeCheckoutSessionFromWebhook::run($this->webhook);

        // Mark posit payment as 'paid'
        $positPayment = $stripeCheckoutSession->model;
        $positPayment->state->transitionTo(Paid::class);
    }
}
