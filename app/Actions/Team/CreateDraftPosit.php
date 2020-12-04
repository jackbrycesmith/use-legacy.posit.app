<?php

namespace App\Actions\Team;

use App\Models\Posit;
use App\Models\Team;
use App\Utils\Constant;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Action;

class CreateDraftPosit extends Action
{
    protected $getAttributesFromConstructor = true;

    public static function routes(Router $router)
    {
        $router->domain(use_posit_domain())
            ->middleware(['web', 'auth'])
            ->post('teams/{team:uuid}/proposals', static::class)
            ->where('team', Constant::PATTERN_UUID)
            ->name('org.create-draft-proposal');
    }

    /**
     * Determine if the user is authorized to make this action.
     *
     * @return bool
     */
    public function authorize(Team $team)
    {
        if ($this->runningAs('object')) {
            return true; // TODO Don't like this, but cant immediately see why below isn't being called right now...
        }

        return $this->can('actionPosit', $team);
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
        $posit = DB::transaction(function () use ($team) {
            // TODO: Potentially move to one nice method?
            // e.g. $user->proposals($team->id)->create();
            $posit = $team->posits()->create();

            // TODO: if use ProposalUser; do it.
            // $orgMember = OrganisationMember::select('id')->where('user_id', $this->user()->id)->first();
            // ProposalUser::create([
            //     'posit_id' => $posit->id,
            //     'organisation_member_id' => $orgMember->id
            // ]);

            return $posit;
        });

        return $posit;
    }

    /**
     * HTTP response
     *
     * @param \App\Models\Posit $posit The posit
     *
     * @return Illuminate\Http\Response
     */
    public function response(Posit $posit)
    {
        return Redirect::route('use.posit.view', ['posit' => $posit->uuid]);
    }
}
