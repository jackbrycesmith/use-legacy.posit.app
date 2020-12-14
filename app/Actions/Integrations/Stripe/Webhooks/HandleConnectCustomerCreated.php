<?php

namespace App\Actions\Integrations\Stripe\Webhooks;

use App\Models\StripeCustomer;
use CloudCreativity\LaravelStripe\Webhooks\ConnectWebhook;
use Lorisleiva\Actions\Action;

class HandleConnectCustomerCreated extends Action
{
    /**
     * Gets the attributes from event.
     *
     * @param \CloudCreativity\LaravelStripe\Webhooks\ConnectWebhook $event The event
     *
     * @return array The attributes from event.
     */
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
        $stripeObject = data_get($this->webhook, 'webhook.data.object');
        $stripeCustomerId = data_get($this->webhook, 'webhook.data.object.id');
        $accountId = data_get($this->webhook, 'account.id');
        if (StripeCustomer::exists($stripeCustomerId)) {
            return;
        }

        $customer = (new StripeCustomer())->fillFrom($stripeObject);
        $customer->stripe_account_id = $accountId;
        $customer->save();
    }
}
