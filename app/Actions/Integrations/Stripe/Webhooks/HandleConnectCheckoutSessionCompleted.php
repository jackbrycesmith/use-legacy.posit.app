<?php

namespace App\Actions\Integrations\Stripe\Webhooks;

use App\Actions\Integrations\Stripe\UpdateStripeCheckoutSessionFromWebhook;
use App\Models\Posit;
use App\Models\PositPayment;
use App\Models\States\PositPayment\Paid;
use App\Models\States\Posit\Accepted;
use App\Models\StripeCheckoutSession;
use App\Models\StripeCustomer;
use CloudCreativity\LaravelStripe\Webhooks\ConnectWebhook;
use Lorisleiva\Actions\Action;

class HandleConnectCheckoutSessionCompleted extends Action
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
     * @todo PHP 8 cleanup...
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

        $positPayment = $stripeCheckoutSession->model;
        if (! is_a($positPayment, PositPayment::class)) {
            logger('🚨 this should not happen');
            return;
        }

        // Add stripe_customer to teamcontact...
        $teamContact = $positPayment->posit->recipient;
        $customerId = data_get($this->webhook, 'webhook.data.object.customer');
        $stripeCustomer = StripeCustomer::find($customerId);

        if ($stripeCustomer && $teamContact) {
            $stripeCustomer->model()->associate($teamContact);
            $stripeCustomer->save();
        }

        // Mark as accepted
        if ($positPayment->type === PositPayment::TYPE_DEPOSIT) {
            $posit = $positPayment->posit;

            $posit->state->transitionTo(Accepted::class);
        }

        // If checkout is paid, mark the posit payment as paid too...
        if ($stripeCheckoutSession->isPaid()) {
            $positPayment->state->transitionTo(Paid::class);
        }
    }
}
