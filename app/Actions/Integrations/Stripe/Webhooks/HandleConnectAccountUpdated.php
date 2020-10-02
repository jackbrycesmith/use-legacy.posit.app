<?php

namespace App\Actions\Integrations\Stripe\Webhooks;

use CloudCreativity\LaravelStripe\Webhooks\ConnectWebhook;
use Lorisleiva\Actions\Action;

class HandleConnectAccountUpdated extends Action
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
        // TODO this seems a bit naive?
        $account = $this->webhook->account;

        $stripeAccountUpdate = data_get($this->webhook, 'webhook.data.object');
        $account->fillFrom($stripeAccountUpdate);
        $account->save();
    }
}
