<?php

namespace App\Actions\UsePositApp;

use App\Actions\Team\CreateDraftProposal;
use App\Models\Proposal;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Lorisleiva\Actions\Action;

class UseProposalNew extends Action
{
    public static function routes(Router $router)
    {
        $router->domain(use_posit_domain())
            ->middleware(['web'])
            ->get('/proposal/new', static::class)
            ->name('use.proposal.new');
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
        $user = $this->user();
        if (is_null($user)) {
            return null;
        }

        if ($user->allTeams()->count() > 1) {
            $this->hasMultipleOrgs = true;
            return null;
        }

        $proposal = (new CreateDraftProposal)->run([
            'team' => $user->currentTeam
        ]);

        return $proposal;
    }

    public function response(?Proposal $proposal)
    {
        if ($this->hasMultipleOrgs) {
            return Redirect::route('use.proposal.new.choose-org');
        }

        if (is_null($proposal)) {
            // Let them try out the editor without being logged in
            return Inertia::render('Use/ProposalNew');
        }

        return Redirect::route('use.proposal.view', ['proposal' => $proposal->uuid]);
    }
}
