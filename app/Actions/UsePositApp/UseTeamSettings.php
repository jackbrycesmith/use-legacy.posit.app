<?php

namespace App\Actions\UsePositApp;

use App\Http\Resources\TeamResource;
use App\Models\Team;
use App\Utils\Constant;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Laravel\Jetstream\Jetstream;
use Lorisleiva\Actions\Action;

class UseTeamSettings extends Action
{
    public static function routes(Router $router)
    {
        $router->domain(use_posit_domain())
            ->middleware(['web', 'auth:sanctum', 'verified'])
            ->get('/team/{team:uuid}/settings', static::class)
            ->where('team', Constant::PATTERN_UUID)
            ->name('use.team.settings');
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
        $team->loadMissing(['owner', 'media', 'stripeAccount']);

        return Inertia::render('Use/TeamSettings', [
            'team' => new TeamResource($team),
            'availableRoles' => array_values(Jetstream::$roles),
            'availablePermissions' => Jetstream::$permissions,
            'defaultPermissions' => Jetstream::$defaultPermissions,
            'permissions' => [
                'canAddTeamMembers' => Gate::check('addTeamMember', $team),
                'canDeleteTeam' => Gate::check('delete', $team),
                'canRemoveTeamMembers' => Gate::check('removeTeamMember', $team),
                'canUpdateTeam' => Gate::check('update', $team),
            ],
        ]);
    }
}
