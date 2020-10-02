<?php

namespace App\Actions\UsePositApp;

use App\Models\StripeAccount;
use App\Models\Team;
use App\Utils\Constant;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Request;
use Lorisleiva\Actions\Action;

class UseTeamStripeConnectAccountLink extends Action
{
    /**
     * Add routes for this action.
     *
     * @param \Illuminate\Routing\Router $router The router
     *
     * @return void
     */
    public static function routes(Router $router)
    {
        $router->domain(use_posit_domain())
            ->middleware(['web', 'auth:sanctum', 'verified'])
            ->get('/team/{team:uuid}/stripe-connect-account-link', static::class)
            ->where('team', Constant::PATTERN_UUID)
            ->name('use.team.stripe-connect-account-link');
    }

    /**
     * Determine if the user is authorized to make this action.
     *
     * @param \App\Models\Team $team The team
     *
     * @return bool
     */
    public function authorize(Team $team)
    {
        return $this->can('update', $team);
    }

    /**
     * Execute the action and return a result.
     *
     * @param \App\Models\Team $team The team
     *
     * @return mixed
     *
     * @see https://stripe.com/docs/connect/connect-onboarding-standard
     */
    public function handle(Team $team)
    {
        // 1. Create or get existing Stripe Account for this team
        $stripeAccount = $team->stripeAccount ?? StripeAccount::createFromStripeApi($team->id, 'standard', $this->stripeAccountCreationParams());

        // 2. Determine account link type & links to return to platform
        $refreshUrl = route('use.team.stripe-connect-account-link', ['team' => $team]);
        $returnUrl = route('use.team.settings', ['team' => $team]);
        $type = $stripeAccount->accountLinkType();

        // 3. Call the Account Links API
        $accountLink = $stripeAccount->stripe()->accountLinks()->create($refreshUrl, $returnUrl, $type);

        return $accountLink->url;
    }

    /**
     * The action http response.
     *
     * @param string $redirectUrl The redirect url
     *
     * @return Illuminate\Http\Response
     */
    public function response(string $redirectUrl)
    {
        if (Request::inertia()) {
            return response('', 409)->header('X-Inertia-Location', $redirectUrl);
        }

        return redirect()->away($redirectUrl);
    }

    /**
     * Any extra params to prefill when creating the stripe account
     *
     * @return array
     *
     * @see https://stripe.com/docs/api/accounts/create?lang=php
     */
    public static function stripeAccountCreationParams(): array
    {
        return [
            'capabilities' => [
                'card_payments' => [
                    'requested' => true
                ],
                'bacs_debit_payments' => [
                    'requested' => true // Hopefully this is OK even if the customer is not GB; expect it to be ignored
                ],
            ]
        ];
    }
}
