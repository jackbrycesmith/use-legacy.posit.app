<?php

namespace App\Actions\Proposal;

use App\Models\Proposal;
use App\Models\StripeCheckoutSession;
use Lorisleiva\Actions\Action;

class CreateProposalStripeCheckoutSessionBacsPayment extends Action
{
    protected $getAttributesFromConstructor = ['proposal', 'amount'];

    /**
     * Execute the action and return a result.
     *
     * @return mixed
     */
    public function handle(Proposal $proposal, int $amount)
    {
        // TODO improve naive approach

        $org = $proposal->organisation;

        $stripeCheckoutSessionApiCreateParams = [
            'mode' => 'payment',
            'payment_method_types' => ['bacs_debit'],
            'cancel_url' => route('pub.proposal.view', ['proposal' => $proposal]),
            'success_url' => route('pub.proposal.view', ['proposal' => $proposal]),
            'client_reference_id' => $proposal->uuid,
            'payment_intent_data' => [
                'setup_future_usage' => 'off_session'
            ],
            'line_items' => [
                [
                    'amount' => $amount,
                    'currency' => 'gbp',
                    'name' => $proposal->uuid,
                    'quantity' => 1
                ]
            ]
        ];

        $checkoutSessionApiResponse = $org->stripeAccount->makeStripeCheckoutSession(
            $stripeCheckoutSessionApiCreateParams
        );

        $stripeCheckoutSession = (new StripeCheckoutSession)->fillFrom(
            $checkoutSessionApiResponse
        );

        $stripeCheckoutSession->stripe_account_id = $org->stripeAccount->id;

        $proposal->stripeCheckoutSession()->save($stripeCheckoutSession);

        return $proposal;
    }
}
