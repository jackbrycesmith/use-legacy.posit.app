<?php

namespace App\Actions\Integrations\Stripe\Webhooks;

use App\Actions\Integrations\Stripe\UpdateStripeCheckoutSessionFromWebhook;
use App\Models\Posit;
use App\Models\PositPayment;
use App\Models\States\Posit\Accepted;
use App\Models\StripeCheckoutSession;
use CloudCreativity\LaravelStripe\Webhooks\ConnectWebhook;
use Lorisleiva\Actions\Action;

class HandleConnectCheckoutSessionCompleted extends Action
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
        if (is_null($stripeCheckoutSession)) {
            // TODO alarm bells here... the checkout session should be found...
            logger('🚨 this should not happen');
            return;
        }

        // If not a proposal payment return..
        if (! (($positPayment = $stripeCheckoutSession->model) instanceof PositPayment)) {
            logger('🚨 this should not happen');
            return;
        }

        // Mark as accepted
        if ($positPayment->type === PositPayment::TYPE_DEPOSIT) {
            $posit = $positPayment->posit;

            $posit->state->transitionTo(Accepted::class);
        }
    }
}
