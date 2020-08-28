<?php

namespace App\Actions\UsePositApp;

use App\Models\Organisation;
use App\Models\StripeAccount;
use App\Utils\Constant;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Request;
use Lorisleiva\Actions\Action;

class UseOrgStripeConnectOnboardStart extends Action
{
    public static function routes(Router $router)
    {
        $router->domain(use_posit_domain())
            ->middleware(['web', 'auth'])
            ->get('/org/{org:uuid}/stripe-connect-onboard-start', static::class)
            ->where('org', Constant::PATTERN_UUID)
            ->name('use.org.stripe-connect-onboard-start');
    }

    /**
     * Determine if the user is authorized to make this action.
     *
     * @return bool
     */
    public function authorize(Organisation $org)
    {
        // TODO more complex auth
        return $this->can('update', $org);
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
    public function handle(Organisation $org)
    {
        // https://stripe.com/docs/connect/connect-onboarding-standard

        // 2. Create/Existing Stripe Account
        $stripeAccount = $org->stripeAccount ?? StripeAccount::createFromStripeApi($org->id);

        // TODO check & stop if already connected?
        // TODO handle disconnected old account/how softdeletes works...

        // 3. Call the Account Links API
        $accountLink = $stripeAccount->stripe()->accountLinks()->create(
            $refreshUrl = route('use.org.stripe-connect-onboard-start', ['org' => $org]),
            $returnUrl = route('use.org.view', ['org' => $org])
        );

        $redirectUrl = $accountLink->url;

        if (Request::inertia()) {
            return response('', 409)->header('X-Inertia-Location', $redirectUrl);
        }

        return redirect()->away($redirectUrl);
    }
}
