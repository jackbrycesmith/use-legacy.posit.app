<?php

namespace App\Actions\PubPositApp;

use App\Http\Resources\ProposalLiteResource;
use App\Models\Proposal;
use App\Utils\Constant;
use Illuminate\Routing\Router;
use Inertia\Inertia;
use Lorisleiva\Actions\Action;

class PubProposalViewAuth extends Action
{
    public static function routes(Router $router)
    {
        $router->domain(pub_posit_domain())
            ->middleware(['web'])
            ->get('/proposal/{proposal:uuid}/auth', static::class)
            ->where('proposal', Constant::PATTERN_UUID)
            ->name('pub.proposal.view.auth');
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
        return Inertia::render('Pub/ProposalViewAuth', [
            'proposal' => new ProposalLiteResource($proposal)
        ]);
    }
}
