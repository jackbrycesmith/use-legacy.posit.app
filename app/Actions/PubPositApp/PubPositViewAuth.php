<?php

namespace App\Actions\PubPositApp;

use App\Http\Resources\PositLiteResource;
use App\Models\Posit;
use App\Utils\Constant;
use Illuminate\Routing\Router;
use Inertia\Inertia;
use Lorisleiva\Actions\Action;

class PubPositViewAuth extends Action
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
            ->middleware(['web'])
            ->get('/posit/{posit:uuid}/auth', static::class)
            ->where('posit', Constant::PATTERN_UUID)
            ->name('pub.posit.view.auth');
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
    public function handle(Posit $posit)
    {
        return Inertia::render('Pub/PositViewAuth', [
            'posit' => new PositLiteResource($posit)
        ]);
    }
}
