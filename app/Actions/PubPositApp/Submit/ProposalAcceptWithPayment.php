<?php

namespace App\Actions\PubPositApp\Submit;

use App\Models\Proposal;
use App\Models\StripeCheckoutSession;
use App\Utils\Constant;
use Illuminate\Routing\Router;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Action;

class ProposalAcceptWithPayment extends Action
{
    /**
     * Specify routes for this action.
     *
     * @param \Illuminate\Routing\Router $router The router
     *
     * @return void
     */
    public static function routes(Router $router)
    {
        $router->domain(pub_posit_domain())
            ->middleware(['web', 'public.proposal.access:ignore-status-check'])
            ->put('/proposal/{proposal:uuid}/accept-with-payment', static::class)
            ->where('proposal', Constant::PATTERN_UUID)
            ->name('pub.proposal.accept-with-payment');
    }

    /**
     * Determine if the user is authorized to make this action.
     *
     * @return bool
     */
    public function authorize(Proposal $proposal)
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Execute the action and return a result.
     *
     * @return mixed
     */
    public function handle(Proposal $proposal)
    {
        // Return existing checkout session
        if ($stripeCheckoutSession = $proposal->depositPayment->stripeCheckoutSession) {
            return $stripeCheckoutSession;
        }

        $checkoutSessionApiResponse = $proposal->team->stripeAccount->makeStripeCheckoutSession(
            $this->stripeCheckoutSessionCreateParams($proposal)
        );

        $stripeCheckoutSession = (new StripeCheckoutSession)->fillFrom(
            $checkoutSessionApiResponse
        );

        $stripeCheckoutSession->stripe_account_id = $proposal->team->stripeAccount->id;

        $proposal->depositPayment->stripeCheckoutSession()->save($stripeCheckoutSession);

        return $stripeCheckoutSession;
    }

    /**
     * { function_description }
     *
     * @param \App\Models\Proposal $proposal The proposal
     *
     * @return array
     */
    protected function stripeCheckoutSessionCreateParams(Proposal $proposal): array
    {
        $stripeAccount = $proposal->team->stripeAccount;

        return [
            'mode' => 'payment',
            'payment_method_types' => $stripeAccount->checkoutSessionPaymentMethodTypes(),
            'cancel_url' => route('pub.proposal.view', ['proposal' => $proposal]),
            'success_url' => route('pub.proposal.view', ['proposal' => $proposal]),
            'client_reference_id' => $proposal->uuid,
            'payment_intent_data' => [
                'setup_future_usage' => 'off_session'
            ],
            'line_items' => [
                [
                    'amount' =>  $proposal->depositPayment->stripe_api_amount,
                    'currency' => Str::lower($proposal->value_currency_code),
                    'name' => $proposal->uuid,
                    'quantity' => 1
                ]
            ]
        ];
    }

    /**
     * The action http response.
     *
     * @return \Illuminate\Http\Response
     */
    public function response(StripeCheckoutSession $stripeCheckoutSession)
    {
        return response()->json([
            'stripe_checkout_session_id' => $stripeCheckoutSession->id,
            'stripe_account_id' => $stripeCheckoutSession->stripe_account_id
        ]);
    }
}
