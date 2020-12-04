<?php

namespace App\Actions\UsePositApp;

use App\Actions\Team\CreateDraftPosit;
use App\Models\Posit;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Action;

class UsePositNew extends Action
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
            ->get('/posit/new', static::class)
            ->name('use.posit.new');
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
        $posit = (new CreateDraftPosit)->run([
            'team' => $this->user()->currentTeam
        ]);

        return $posit;
    }

    /**
     * The action HTTP response.
     *
     * @param \App\Models\Posit $posit The posit
     *
     * @return \Illuminate\Http\Response
     */
    public function response(Posit $posit)
    {
        return Redirect::route('use.posit.view', $posit);
    }
}
