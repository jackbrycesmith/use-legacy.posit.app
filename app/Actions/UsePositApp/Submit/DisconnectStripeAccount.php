<?php

namespace App\Actions\UsePositApp\Submit;

use App\Models\Organisation;
use Illuminate\Routing\Router;
use Lorisleiva\Actions\Action;

class DisconnectStripeAccount extends Action
{
    public static function routes(Router $router)
    {
        $router->domain(use_posit_domain())->middleware(['web', 'auth'])->put('/org/{org:uuid}/stripe-disconnect', static::class)->name('use.submit.disconnect-stripe-account');
    }

    /**
     * Determine if the user is authorized to make this action.
     *
     * @return bool
     */
    public function authorize(Organisation $org)
    {
        // TODO more complex auth
        return $this->can('disconnectStripeAccount', $org);
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
        $org->stripeAccount->stripe()->deauthorize();

        return true;
    }

    public function response($success)
    {
        return $success
            ? response()->noContent()
            : response()->response(null, 400);
    }
}
