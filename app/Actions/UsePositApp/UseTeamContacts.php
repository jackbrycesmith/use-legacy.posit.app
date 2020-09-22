<?php

namespace App\Actions\UsePositApp;

use App\Http\Resources\TeamResource;
use App\Models\Team;
use App\Utils\Constant;
use Illuminate\Routing\Router;
use Inertia\Inertia;
use Lorisleiva\Actions\Action;

class UseTeamContacts extends Action
{
    /**
     * Add any routes for this action.
     *
     * @param \Illuminate\Routing\Router $router The router
     *
     * @return void
     */
    public static function routes(Router $router)
    {
        $router->domain(use_posit_domain())
            ->middleware(['web', 'auth:sanctum', 'verified'])
            ->get('/team/{team:uuid}/contacts', static::class)
            ->where('team', Constant::PATTERN_UUID)
            ->name('use.team.contacts');
    }

    /**
     * Determine if the user is authorized to make this action.
     *
     * @return bool
     */
    public function authorize(Team $team)
    {
        return $this->can('view', $team);
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
    public function handle(Team $team)
    {
        $team->loadMissing(['contacts']);

        return Inertia::render('Use/OrgContacts', [
            'org' => new TeamResource($team)
        ]);
    }
}
