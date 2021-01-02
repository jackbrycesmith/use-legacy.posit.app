<?php

namespace App\Actions\Integrations\Stripe\Webhooks;

use App\Models\Team;
use App\Notifications\Team\TeamStripeAccountDisconnected;
use CloudCreativity\LaravelStripe\Webhooks\ConnectWebhook;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Action;

class HandleConnectAccountApplicationDeauthorized extends Action
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
        $account = $this->webhook->account;
        $accountOwner = $account->owner;

        DB::transaction(function () use ($account) {
            $account->stripeCustomers()->get()->each->delete();
            $account->delete();
        });

        if (is_a($accountOwner, Team::class)) {
            $accountOwner->notify(new TeamStripeAccountDisconnected());
        }
    }
}
