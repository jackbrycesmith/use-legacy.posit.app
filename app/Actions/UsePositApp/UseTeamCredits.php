<?php

namespace App\Actions\UsePositApp;

use App\Http\Resources\TeamResource;
use App\Models\Team;
use App\Utils\Constant;
use App\Utils\Paddle;
use Illuminate\Routing\Router;
use Lorisleiva\Actions\Action;

class UseTeamCredits extends Action
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
            ->get('/team/{team:uuid}/credits', static::class)
            ->where('team', Constant::PATTERN_UUID)
            ->name('use.team.credits');
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
        return response()->inertiable('Use/TeamCredits', [
            'team' => fn() => $this->teamResource($team),
            'paddle_vendor_id' => fn() => (int) config('cashier.vendor_id'),
            'paddle_products' => fn() => array_values(Paddle::$products),
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
        $team->loadMissing(['media', 'stripeAccount'])
            ->loadCount(['publishedProposals']);

        return new TeamResource($team);
    }
}
