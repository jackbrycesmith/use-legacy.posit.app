<?php

namespace App\Actions\Integrations\Stripe\Webhooks;

use App\Actions\Integrations\Stripe\UpdateStripeCheckoutSessionFromWebhook;
use App\Models\Proposal;
use App\Models\ProposalPayment;
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
        if (! (($proposalPayment = $stripeCheckoutSession->model) instanceof ProposalPayment)) {
            logger('🚨 this should not happen');
            return;
        }

        // Mark as accepted
        if ($proposalPayment->type === ProposalPayment::TYPE_DEPOSIT) {
            $proposal = $proposalPayment->proposal;

            if ($proposal->status !== Proposal::STATUS_ACCEPTED) {
                $proposal->setStatus(Proposal::STATUS_ACCEPTED);
            }
        }
    }
}
