<?php

namespace App\Actions\UsePositApp;

use App\Models\Organisation;
use CloudCreativity\LaravelStripe\Facades\Stripe;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Request;
use Lorisleiva\Actions\Action;

class UseOrgStripeConnectOauthStart extends Action
{
    public static function routes(Router $router)
    {
        $router->domain(use_posit_domain())->middleware(['web', 'auth'])->get('/org/{org:uuid}/stripe-connect-oauth-start', static::class)->name('use.org.stripe-connect-oauth-start');
    }

    /**
     * Determine if the user is authorized to make this action.
     *
     * @return bool
     */
    public function authorize(Organisation $org)
    {
        return $this->can('startStripeConnectOauth', $org);
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
        // TODO specify exact oauth redirectUri & ensure its a configured value in the stripe dashboard
        if (Request::inertia()) {
            return response('', 409)
                ->header('X-Inertia-Location', Stripe::authorizeUrl()->login()->readWrite()->toString());
        }

        return Stripe::authorizeUrl()->login()->readWrite()->redirect();
    }
}
