<?php

namespace App\Actions\UsePositApp\Submit;

use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Lorisleiva\Actions\Action;

class UpsertProposalContent extends Action
{
    public static function routes(Router $router)
    {
        $router->domain(use_posit_domain())->middleware(['web', 'auth'])->put('/proposal/{proposal:uuid}/upsert-content', static::class)->name('use.submit.upsert-proposal-content');
    }

    /**
     * Determine if the user is authorized to make this action.
     *
     * @return bool
     */
    public function authorize(Proposal $proposal)
    {
        return $this->can('update', $proposal);
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    public function rules()
    {
        return []; // TODO
    }

    /**
     * Execute the action and return a result.
     *
     * @return mixed
     */
    public function handle(Request $request, Proposal $proposal)
    {
        // TODO like redis locking & stuff

        // TODO this is extremely naive/not production ready
        $proposal->proposalContents()->updateOrCreate(
            ['proposal_id' => $proposal->id],
            ['content' => $request->all()]
        );

    }
}
