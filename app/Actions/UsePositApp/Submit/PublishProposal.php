<?php

namespace App\Actions\UsePositApp\Submit;

use App\Models\Proposal;
use App\Utils\Constant;
use Illuminate\Routing\Router;
use Lorisleiva\Actions\Action;

class PublishProposal extends Action
{
    /**
     * Specify routes for this action.
     *
     * @param \Illuminate\Routing\Router $router The router
     *
     * @return void
     */
    public static function routes(Router $router)
    {
        $router->domain(use_posit_domain())
            ->middleware(['web', 'auth:sanctum', 'verified'])
            ->put('/proposal/{proposal:uuid}/publish', static::class)
            ->where('proposal', Constant::PATTERN_UUID)
            ->name('use.submit.publish-proposal');
    }

    /**
     * Determine if the user is authorized to make this action.
     *
     * @return bool
     */
    public function authorize(Proposal $proposal)
    {
        return $this->can('publish', $proposal);
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
        // TODO validate whether proposal is in a state that can be published.
        $proposal->setStatus(Proposal::STATUS_PUBLISHED);
        $proposal->save();

        return $proposal;
    }

    /**
     * The action HTTP response.
     *
     * @return \Illuminate\Http\Response
     */
    public function response(Proposal $proposal)
    {
        return response()->noContent();
    }
}
