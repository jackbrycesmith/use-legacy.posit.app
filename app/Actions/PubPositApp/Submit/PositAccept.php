<?php

namespace App\Actions\PubPositApp\Submit;

use App\Models\Posit;
use App\Models\States\Posit\Accepted;
use App\Utils\Constant;
use Illuminate\Routing\Router;
use Lorisleiva\Actions\Action;

class PositAccept extends Action
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
            ->put('/posit/{posit:uuid}/accept', static::class)
            ->where('posit', Constant::PATTERN_UUID)
            ->name('pub.posit.accept');
    }

    /**
     * Determine if the user is authorized to make this action.
     *
     * @param \App\Models\Posit $posit The posit
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
     * @param \App\Models\Posit $posit The posit
     *
     * @return mixed
     */
    public function handle(Posit $posit)
    {
        $posit->state->transitionTo(Accepted::class);
    }

    /**
     * The action http response.
     *
     * @return \Illuminate\Http\Response
     */
    public function response()
    {
        return response()->noContent();
    }
}
