<?php

namespace App\Actions\UsePositApp;

use App\Http\Resources\OrganisationResource;
use App\Http\Resources\ProposalResource;
use App\Http\Resources\TeamResource;
use Illuminate\Routing\Router;
use Lorisleiva\Actions\Action;

class UseIndex extends Action
{
    public static function routes(Router $router)
    {
        $router->domain(use_posit_domain())
            ->middleware(['web', 'auth:sanctum', 'verified'])
            ->get('/', static::class)
            ->name('use.index');
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
        $team = $this->user()->currentTeam()->with(['proposals', 'media'])->first();

        return $team;
    }

    public function response($team)
    {
        return response()->inertiable('Use/Index', [
            'org' => new TeamResource($team),
        ]);
    }
}
