<?php

namespace App\Actions\UsePositApp\Submit;

use App\Models\Team;
use App\Utils\Constant;
use Illuminate\Routing\Router;
use Lorisleiva\Actions\Action;

class DeleteTeamLogo extends Action
{
    public static function routes(Router $router)
    {
        $router->domain(use_posit_domain())
            ->middleware(['web', 'auth:sanctum', 'verified'])
            ->delete('/teams/{team:uuid}/logo', static::class)
            ->where('team', Constant::PATTERN_UUID)
            ->name('teams.delete-logo');
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
        return [];
    }

    /**
     * Execute the action and return a result.
     *
     * @return mixed
     */
    public function handle(Team $team)
    {
        $team->deleteLogo();

        return back(303)->with('status', 'team-logo-deleted');
    }
}
