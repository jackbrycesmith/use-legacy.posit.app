<?php

namespace App\Actions\UsePositApp\Submit;

use App\Models\Posit;
use App\Models\States\Posit\Published;
use App\Utils\Constant;
use Illuminate\Routing\Router;
use Lorisleiva\Actions\Action;

class PublishPosit extends Action
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
            ->put('/posit/{posit:uuid}/publish', static::class)
            ->where('posit', Constant::PATTERN_UUID)
            ->name('use.submit.publish-posit');
    }

    /**
     * Determine if the user is authorized to make this action.
     *
     * @return bool
     */
    public function authorize(Posit $posit)
    {
        return $this->can('publish', $posit);
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
        // TODO validate whether proposal is in a state that can be published.
        $posit->state->transitionTo(Published::class);

        return $posit;
    }

    /**
     * The action HTTP response.
     *
     * @return \Illuminate\Http\Response
     */
    public function response(Posit $posit)
    {
        return response()->noContent();
    }
}
