<?php

namespace App\Actions\UsePositApp\Submit;

use App\Models\Team;
use App\Utils\Constant;
use Illuminate\Routing\Router;
use Lorisleiva\Actions\Action;

class UpdateTeamInfo extends Action
{
    protected $errorBag = 'updateTeamInfo';

    public static function routes(Router $router)
    {
        $router->domain(use_posit_domain())
            ->middleware(['web', 'auth:sanctum', 'verified'])
            ->put('/teams/{team:uuid}/update-info', static::class)
            ->where('team', Constant::PATTERN_UUID)
            ->name('teams.update-info');
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
        return [
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'max:5120'],
        ];
    }

    /**
     * Execute the action and return a result.
     *
     * @return mixed
     */
    public function handle(Team $team)
    {
        $input = $this->validated();

        if (isset($input['logo'])) {
            $team->updateLogo($input['logo']);
        }

        $team->forceFill([
            'name' => $input['name'],
        ])->save();

        return back(303);
    }
}
