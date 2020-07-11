<?php

namespace App\Actions\Proposal;

use App\Http\Resources\ProposalResource;
use App\Models\Proposal;
use Illuminate\Routing\Router;
use Inertia\Inertia;
use Lorisleiva\Actions\Action;

class GetUseProposalView extends Action
{
    public static function routes(Router $router)
    {
        // Would be nice to specify the domain route here...
        $router->middleware(['web', 'auth'])->get('proposals/{proposal:uuid}', static::class)->name('use.proposal.view');
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
        // TODO load contents, recipients other things.
        return $this->proposal->loadMissing(['organisation', 'users']);
    }

    public function response(Proposal $proposal)
    {
        return Inertia::render('Use/ProposalDetailsIndex', [
            'proposal' => new ProposalResource($proposal)
        ]);
    }
}
