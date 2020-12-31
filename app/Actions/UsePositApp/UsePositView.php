<?php

namespace App\Actions\UsePositApp;

use App\Http\Resources\PositResource;
use App\Models\Posit;
use App\Utils\Constant;
use Illuminate\Routing\Router;
use Lorisleiva\Actions\Action;

class UsePositView extends Action
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
            ->get('/posit/{posit:uuid}', static::class)
            ->where('posit', Constant::PATTERN_UUID)
            ->name('use.posit.view');
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
        return $this->can('view', $posit);
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
        return response()->inertiable('Use/PositView', [
            'posit' => fn() => $this->getPositResource($posit)
        ]);
    }

    /**
     * Returns the proposal resource
     *
     * @param \App\Models\Posit $posit The posit
     *
     * @return PositResource
     */
    protected function getPositResource(Posit $posit)
    {
        $posit->loadMissing([
            'team', 'creator', 'team.contacts', 'team.stripeAccount', 'depositPayment', 'recipient', 'video'
        ]);

        return new PositResource($posit);
    }
}
