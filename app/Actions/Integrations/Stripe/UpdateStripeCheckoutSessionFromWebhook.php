<?php

namespace App\Actions\Integrations\Stripe;

use App\Models\StripeCheckoutSession;
use CloudCreativity\LaravelStripe\Webhooks\ConnectWebhook;
use Lorisleiva\Actions\Action;

class UpdateStripeCheckoutSessionFromWebhook extends Action
{
    protected $getAttributesFromConstructor = true;

    /**
     * Execute the action and return a result.
     *
     * @return mixed
     */
    public function handle(ConnectWebhook $event)
    {
        $stripeObject = data_get($event, 'webhook.data.object');

        $stripeCheckoutSession = StripeCheckoutSession::find($stripeObject->id);

        if (! is_null($stripeCheckoutSession)) {
            $stripeCheckoutSession->fillFrom($stripeObject)->save();
        }

        return $stripeCheckoutSession;
    }
}
