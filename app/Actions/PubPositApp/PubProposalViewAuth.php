<?php

namespace App\Actions\PubPositApp;

use Illuminate\Routing\Router;
use Lorisleiva\Actions\Action;

class PubProposalViewAuth extends Action
{
    public static function routes(Router $router)
    {
        $router->domain(pub_posit_domain())->middleware(['web'])->get('/proposal/{proposal:uuid}/auth', static::class)->name('pub.proposal.view.auth');
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
        // Execute the action.
    }
}
