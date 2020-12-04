<?php

namespace App\Actions\UsePositApp;

use App\Http\Resources\PositResource;
use App\Models\Posit;
use App\Utils\Constant;
use Illuminate\Routing\Router;
use Inertia\Inertia;
use Lorisleiva\Actions\Action;

class UsePositView extends Action
{
    public static function routes(Router $router)
    {
        $router->domain(use_posit_domain())
            ->middleware(['web', 'auth:sanctum', 'verified'])
            ->get('/posit/{posit:uuid}', static::class)
            ->where('posit', Constant::PATTERN_UUID)
            ->name('use.posit.view');
    }

    /**
     * Determine if the user is authorized to make this action.
     *
     * @param \App\Models\Posit $posit The proposal
     *
     * @return bool
     */
    public function authorize(Posit $posit)
    {
        return $this->can('view', $posit);
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
     * @param \App\Models\Posit $posit The proposal
     *
     * @return mixed
     */
    public function handle(Posit $posit)
    {
        return Inertia::render('Use/ProposalView', [
            'proposal' => fn() => $this->getProposalResource($posit)
        ]);
    }

    /**
     * Returns the proposal resource
     *
     * @param \App\Models\Posit $posit The proposal
     *
     * @return PositResource
     */
    protected function getProposalResource(Posit $posit)
    {
        $posit->loadMissing([
            'team', 'creator', 'team.contacts', 'team.stripeAccount', 'positContent', 'depositPayment', 'recipient', 'video'
        ]);

        return new PositResource($posit);
    }
}
