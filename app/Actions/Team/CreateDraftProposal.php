<?php

namespace App\Actions\Team;

use App\Models\Proposal;
use App\Models\Team;
use App\Utils\Constant;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Action;

class CreateDraftProposal extends Action
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

        return $this->can('actionProposal', $team);
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
        $proposal = DB::transaction(function () use ($team) {
            // TODO: Potentially move to one nice method?
            // e.g. $user->proposals($team->id)->create();
            $proposal = $team->proposals()->create();

            // TODO: if use ProposalUser; do it.
            // $orgMember = OrganisationMember::select('id')->where('user_id', $this->user()->id)->first();
            // ProposalUser::create([
            //     'proposal_id' => $proposal->id,
            //     'organisation_member_id' => $orgMember->id
            // ]);

            return $proposal;
        });

        return $proposal;
    }

    /**
     * HTTP response
     *
     * @param \App\Models\Proposal $proposal The proposal
     *
     * @return Illuminate\Http\Response
     */
    public function response(Proposal $proposal)
    {
        return Redirect::route('use.proposal.view', ['proposal' => $proposal->uuid]);
    }
}
