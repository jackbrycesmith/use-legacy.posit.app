<?php

namespace App\Actions\UsePositApp;

use App\Http\Resources\PositResource;
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
        return response()->inertiable('Use/Index', [
            'team' => fn() => $this->teamResource($this->user()),
            'posits' => fn() => $this->proposalsResource($this->user())
        ]);
    }

    protected function teamResource($user)
    {
        $team = $user->currentTeam()
            ->with(['media', 'stripeAccount'])
            ->withCount(['publishedPosits'])
            ->first();

        return new TeamResource($team);
    }

    protected function proposalsResource($user)
    {
        $posits = $user->currentTeam->posits()
            ->with(['recipient'])
            ->paginate(10);

        return PositResource::collection($posits);
    }
}
