<?php

namespace App\Actions\UsePositApp\Submit;

use App\Models\Team;
use App\Utils\Constant;
use App\Utils\Paddle;
use Illuminate\Routing\Router;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\Action;

class TeamCreditsPaddlePayLink extends Action
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
            ->put('/teams/{team:uuid}/credits-paddle-pay-link', static::class)
            ->where('team', Constant::PATTERN_UUID)
            ->name('use.team.credits-paddle-pay-link');
    }

    /**
     * Determine if the user is authorized to make this action.
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
        return [
            'product_id' => [
                'required',
                'integer',
                Rule::in(array_keys(Paddle::$products))
            ]
        ];
    }

    /**
     * Execute the action and return a result.
     *
     * @return mixed
     */
    public function handle(Team $team)
    {
        $payLink = $team->chargeProduct($this->validated()['product_id']);

        return $payLink;
    }

    /**
     * The action http response.
     *
     * @return \Illuminate\Http\Response
     */
    public function response($payLink)
    {
        return response()->json([
            'pay_link' => $payLink
        ]);
    }
}
