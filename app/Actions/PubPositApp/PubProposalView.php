<?php

namespace App\Actions\PubPositApp;

use App\Http\Resources\ProposalLiteResource;
use App\Http\Resources\ProposalResource;
use App\Models\Proposal;
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
     * @return mixed
     */
    public function handle(Proposal $proposal)
    {
        if ($proposal->requiresLiteResource()) {
            $proposalResource = new ProposalLiteResource($proposal);
        } else {
            $proposal->loadMissing(['proposalContent', 'stripeCheckoutSession']);
            $proposalResource = new ProposalResource($proposal);
        }

        return Inertia::render('Pub/ProposalView', [
            'proposal' => $proposalResource,
            'stripe_pub_key' => config('services.stripe.key')
        ]);
    }
}
