<?php

namespace App\Actions\UsePositApp;

use CloudCreativity\LaravelStripe\Facades\Stripe;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Request;
use Lorisleiva\Actions\Action;

class UseOrgStripeConnectOauthStart extends Action
{
    public static function routes(Router $router)
    {
        // TODO: auth check etc
        $router->middleware(['web', 'auth'])->get('/org/stripe-connect-oauth-start', static::class)->name('use.org.stripe-connect-oauth-start');
    }

    /**
     * Determine if the user is authorized to make this action.
     *
     * @return bool
     */
    public function authorize()
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
    public function handle()
    {
        if (Request::inertia()) {
            return response('', 409)
                ->header('X-Inertia-Location', Stripe::authorizeUrl()->readWrite()->toString());
        }

        return Stripe::authorizeUrl()->readWrite()->redirect();
    }
}
