<?php

namespace App\Actions\Organisation;

use App\Models\Organisation;
use App\Models\OrganisationMember;
use App\Models\Proposal;
use App\Models\ProposalUser;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Lorisleiva\Actions\Action;

class CreateDraftProposal extends Action
{
    protected $getAttributesFromConstructor = true;

    public static function routes(Router $router)
    {
        // $router->middleware(['web', 'auth'])->get('author/{author}/articles', static::class);
        // TODO middleware?
        $router->post('orgs/{organisation:uuid}/proposals', static::class)->name('org.create-draft-proposal');
    }

    /**
     * Determine if the user is authorized to make this action.
     *
     * @return bool
     */
    public function authorize(Organisation $organisation)
    {
        if ($this->runningAs('object')) {
            return true; // TODO Don't like this, but cant immediately see why below isn't being called right now...
        }

        return $this->can('createDraftProposal', $organisation);
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
    public function handle(Organisation $organisation)
    {
        $proposal = DB::transaction(function () use ($organisation) {
            // TODO: Potentially move to one nice method?
            // e.g. $user->proposals($organisation->id)->create();
            $proposal = $organisation->proposals()->create();

            $orgMember = OrganisationMember::select('id')->where('user_id', $this->user()->id)->first();
            ProposalUser::create([
                'proposal_id' => $proposal->id,
                'organisation_member_id' => $orgMember->id
            ]);

            return $proposal;
        });

        return $proposal;
    }

    public function response(Proposal $proposal)
    {
        return Redirect::route('use.proposal.view', ['proposal' => $proposal->uuid]);
        // Kinda stumped here on what to return, e.g. inertia response? Pure json if axios?
    }
}
