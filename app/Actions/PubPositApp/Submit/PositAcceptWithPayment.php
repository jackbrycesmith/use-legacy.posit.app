<?php

namespace App\Actions\PubPositApp\Submit;

use App\Models\Posit;
use App\Models\StripeCheckoutSession;
use App\Utils\Constant;
use Illuminate\Routing\Router;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Action;

class PositAcceptWithPayment extends Action
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
            ->middleware(['web', 'public.posit.access:ignore-status-check'])
            ->put('/posit/{posit:uuid}/accept-with-payment', static::class)
            ->where('posit', Constant::PATTERN_UUID)
            ->name('pub.posit.accept-with-payment');
    }

    /**
     * Determine if the user is authorized to make this action.
     *
     * @return bool
     */
    public function authorize(Posit $posit)
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
    public function handle(Posit $posit)
    {
        // Return existing checkout session
        if ($stripeCheckoutSession = $posit->depositPayment->stripeCheckoutSession) {
            return $stripeCheckoutSession;
        }

        // TODO: check if stripeCheckoutSession has a payment failed?

        $checkoutSessionApiResponse = $posit->team->stripeAccount->makeStripeCheckoutSession(
            $this->stripeCheckoutSessionCreateParams($posit)
        );

        $stripeCheckoutSession = (new StripeCheckoutSession)->fillFrom(
            $checkoutSessionApiResponse
        );

        $stripeCheckoutSession->stripe_account_id = $posit->team->stripeAccount->id;

        $posit->depositPayment->stripeCheckoutSession()->save($stripeCheckoutSession);

        return $stripeCheckoutSession;
    }

    /**
     * { function_description }
     *
     * @param \App\Models\Posit $posit The posit
     *
     * @return array
     */
    protected function stripeCheckoutSessionCreateParams(Posit $posit): array
    {
        $stripeAccount = $posit->team->stripeAccount;
        $currencyCode = Str::lower($posit->value_currency_code);
        $paymentMethodTypes = $stripeAccount->checkoutSessionPaymentMethodTypes($currencyCode);
        $paymentIntentData = in_array('bacs_debit', $paymentMethodTypes) ? [
            // Must have for bacs: https://stripe.com/docs/payments/bacs-debit/accept-a-payment#create-session
            'setup_future_usage' => 'off_session'
        ]: [
            'setup_future_usage' => 'on_session'
        ];
        $stripeCustomerId = optional($posit->recipient->stripeCustomer)->id;

        return [
            'mode' => 'payment',
            'payment_method_types' => $paymentMethodTypes,
            'cancel_url' => route('pub.posit.view', ['posit' => $posit]),
            'success_url' => route('pub.posit.view', ['posit' => $posit]),
            'client_reference_id' => $posit->uuid,
            'payment_intent_data' => $paymentIntentData,
            'customer' => $stripeCustomerId,
            'line_items' => [
                [
                    'amount' =>  $posit->depositPayment->stripe_api_amount,
                    'currency' => $currencyCode,
                    'name' => $posit->uuid,
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
