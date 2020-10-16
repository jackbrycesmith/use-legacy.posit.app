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
            ->middleware(['web', 'auth'])
            ->get('/proposal/{proposal:uuid}', static::class)
            ->where('proposal', Constant::PATTERN_UUID)
            ->name('use.proposal.view');
    }

    /**
     * Determine if the user is authorized to make this action.
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
     * @return mixed
     */
    public function handle()
    {
        return $this->proposal->loadMissing([
            'team', 'team.contacts', 'team.stripeAccount', 'proposalContent', 'recipient', 'video'
        ]);
    }

    public function response(Proposal $proposal)
    {
        return Inertia::render('Use/ProposalView', [
            'proposal' => new ProposalResource($proposal)
        ]);
    }
}
