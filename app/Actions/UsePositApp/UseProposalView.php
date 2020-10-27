<?php

namespace App\Actions\UsePositApp;

use App\Http\Resources\ProposalResource;
use App\Models\Proposal;
use App\Utils\Constant;
use Illuminate\Routing\Router;
use Inertia\Inertia;
use Lorisleiva\Actions\Action;

class UseProposalView extends Action
{
    public static function routes(Router $router)
    {
        $router->domain(use_posit_domain())
            ->middleware(['web', 'auth:sanctum', 'verified'])
            ->get('/proposal/{proposal:uuid}', static::class)
            ->where('proposal', Constant::PATTERN_UUID)
            ->name('use.proposal.view');
    }

    /**
     * Determine if the user is authorized to make this action.
     *
     * @param \App\Models\Proposal $proposal The proposal
     *
     * @return bool
     */
    public function authorize(Proposal $proposal)
    {
        return $this->can('view', $proposal);
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
     * @param \App\Models\Proposal $proposal The proposal
     *
     * @return mixed
     */
    public function handle(Proposal $proposal)
    {
        return Inertia::render('Use/ProposalView', [
            'proposal' => fn() => $this->getProposalResource($proposal)
        ]);
    }

    /**
     * Returns the proposal resource
     *
     * @param \App\Models\Proposal $proposal The proposal
     *
     * @return ProposalResource
     */
    protected function getProposalResource(Proposal $proposal)
    {
        $proposal->loadMissing([
            'team', 'creator', 'team.contacts', 'team.stripeAccount', 'proposalContent', 'depositPayment', 'recipient', 'video'
        ]);

        return new ProposalResource($proposal);
    }
}
