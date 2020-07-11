<?php

namespace App\Actions\Proposal;

use App\Http\Resources\OrganisationResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Routing\Router;
use Inertia\Inertia;
use Lorisleiva\Actions\Action;

class NewProposalChooseOrg extends Action
{
    public static function routes(Router $router)
    {
        // Would be nice to specify the domain route here...
        $router->middleware(['web', 'auth'])->get('proposals/new/choose-org', static::class)->name('use.proposal.new.choose-org');
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
        return $this->user()->organisations;
    }

    public function response(Collection $organisations)
    {
        return Inertia::render('Proposals/New/ChooseOrg', [
            'orgs' => OrganisationResource::collection($organisations)
        ]);
    }
}
