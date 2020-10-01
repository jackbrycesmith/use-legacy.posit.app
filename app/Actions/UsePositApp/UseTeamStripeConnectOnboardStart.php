<?php

namespace App\Actions\UsePositApp;

use App\Models\StripeAccount;
use App\Models\Team;
use App\Utils\Constant;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Request;
use Lorisleiva\Actions\Action;

class UseTeamStripeConnectOnboardStart extends Action
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
            ->get('/team/{team:uuid}/stripe-connect-onboard-start', static::class)
            ->where('team', Constant::PATTERN_UUID)
            ->name('use.team.stripe-connect-onboard-start');
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
    public function handle(Team $team)
    {
        // https://stripe.com/docs/connect/connect-onboarding-standard

        // 2. Create/Existing Stripe Account
        $stripeAccount = $team->stripeAccount ?? StripeAccount::createFromStripeApi($team->id);

        // TODO check & stop if already connected?
        // TODO handle disconnected old account/how softdeletes works...

        // 3. Call the Account Links API
        $accountLink = $stripeAccount->stripe()->accountLinks()->create(
            $refreshUrl = route('use.team.stripe-connect-onboard-start', ['team' => $team]),
            $returnUrl = route('use.team.settings', ['team' => $team])
        );

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
}
