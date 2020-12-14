<?php

namespace App\Actions\Integrations\Stripe\Webhooks;

use App\Actions\Integrations\Stripe\UpdateStripeCheckoutSessionFromWebhook;
use App\Models\PositPayment;
use App\Models\States\PositPayment\Pending;
use App\Notifications\Team\TeamPositPaymentFailed;
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
        $stripeCheckoutSession = UpdateStripeCheckoutSessionFromWebhook::run($this->webhook);

        // Mark payment as pending again
        $positPayment = $stripeCheckoutSession->model;
        if (! is_a($positPayment, PositPayment::class)) {
            logger('🚨 this should not happen');
            return;
        }

        // Mark posit payment as 'pending' again
        $positPayment->state->transitionTo(Pending::class);

        $team = $positPayment->posit->team;
        $recipient = $positPayment->posit->recipient;

        $team->notify(new TeamPositPaymentFailed());
        // TODO: notify team contact recipient too that it failed & they need to try again...
    }
}
