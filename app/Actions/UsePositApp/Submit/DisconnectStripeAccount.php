<?php

namespace App\Actions\UsePositApp\Submit;

use App\Models\Team;
use App\Utils\Constant;
use Illuminate\Routing\Router;
use Lorisleiva\Actions\Action;
use Stripe\Exception\OAuth\InvalidClientException;

class DisconnectStripeAccount extends Action
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
        $router->domain(use_posit_domain())
            ->middleware(['web', 'auth:sanctum', 'verified'])
            ->put('/team/{team:uuid}/stripe-disconnect', static::class)
            ->where('team', Constant::PATTERN_UUID)
            ->name('use.submit.disconnect-stripe-account');
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
     * @param \App\Models\Team $team The team
     *
     * @return mixed
     */
    public function handle(Team $team)
    {
        try {
            $team->stripeAccount->stripe()->deauthorize();
        } catch (InvalidClientException $exception) {
            // Somethings gone wrong here? Trying to disconnect from an account that i longer have access to
            // So will soft delete...
            $team->stripeAccount->delete();
        }

        return true;
    }


    /**
     * The action http response.
     *
     * @param boolean $success The success of the action
     *
     * @return \Illuminate\Http\Response
     */
    public function response($success)
    {
        return $success
            ? response()->noContent()
            : response()->response(null, 400);
    }
}
