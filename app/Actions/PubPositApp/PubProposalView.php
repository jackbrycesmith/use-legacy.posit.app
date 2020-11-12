<?php

namespace App\Actions\PubPositApp;

use App\Http\Resources\ProposalLiteResource;
use App\Http\Resources\ProposalResource;
use App\Models\Proposal;
use App\Utils\Constant;
use Illuminate\Routing\Router;
use Inertia\Inertia;
use Lorisleiva\Actions\Action;

class PubProposalView extends Action
{
    public static function routes(Router $router)
    {
        $router->domain(pub_posit_domain())
            ->middleware(['web', 'public.proposal.access'])
            ->get('/proposal/{proposal:uuid}', static::class)
            ->where('proposal', Constant::PATTERN_UUID)
            ->name('pub.proposal.view');
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
     * @param \App\Models\Proposal $proposal The proposal
     *
     * @return mixed
     */
    public function handle(Proposal $proposal)
    {
        return Inertia::render('Pub/ProposalView', [
            'proposal' => fn() => $this->proposalResource($proposal),
            'is_limited_view' => fn() => $proposal->requiresLiteResource(),
            'stripe_pub_key' => fn() => config('services.stripe.key')
        ]);
    }

    /**
     * Returns the proposal resource
     *
     * @param \App\Models\Proposal $proposal The proposal
     *
     * @return ProposalLiteResource|ProposalResource
     */
    protected function proposalResource(Proposal $proposal)
    {
        if ($proposal->requiresLiteResource()) {
            return new ProposalLiteResource($proposal);
        }

        $proposal->loadMissing([
            'proposalContent',
            'creator',
            'video',
            'depositPayment',
            'depositPayment.stripeCheckoutSession',
            'recipient'
        ]);

        return new ProposalResource($proposal);
    }
}
