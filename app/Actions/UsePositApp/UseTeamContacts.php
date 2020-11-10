<?php

namespace App\Actions\UsePositApp;

use App\Http\Resources\TeamContactResource;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use App\Utils\Constant;
use Illuminate\Routing\Router;
use Lorisleiva\Actions\Action;

class UseTeamContacts extends Action
{
    /**
     * Add routes for this action.
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
     * @param \App\Models\Team $team The team
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
     * @param \App\Models\Team $team The team
     *
     * @return mixed
     */
    public function handle(Team $team)
    {
        return response()->inertiable('Use/TeamContacts', [
            'team' => fn() => $this->teamResource($team),
            'contacts' => fn() => $this->contactsResource($team)
        ]);
    }

    /**
     * Get the team resource.
     *
     * @param \App\Models\Team $team The team
     *
     * @return TeamResource The team resource.
     */
    protected function teamResource(Team $team)
    {
        $team->loadMissing(['media']);

        return new TeamResource($team);
    }

    /**
     * Get the contacts resource.
     *
     * @param \App\Models\Team $team The team
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    protected function contactsResource(Team $team)
    {
        $contacts = $team->contacts()
            ->paginate(10);

        return TeamContactResource::collection($contacts);
    }
}
