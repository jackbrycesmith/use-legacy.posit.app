<?php

namespace App\Actions\UsePositApp\Submit;

use App\Models\Proposal;
use App\Utils\Constant;
use Illuminate\Routing\Router;
use Lorisleiva\Actions\Action;

class UpsertProposalName extends Action
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
            ->put('/proposal/{proposal:uuid}/upsert-name', static::class)
            ->where('proposal', Constant::PATTERN_UUID)
            ->name('use.submit.upsert-proposal-name');
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
        return $this->can('update', $proposal);
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'bail',
                'nullable',
                'string',
                'max:255'
            ]
        ];
    }

    /**
     * Execute the action and return a result.
     *
     * @return mixed
     */
    public function handle(Proposal $proposal)
    {
        $proposal->update($this->validated());
    }

    /**
     * The action http response.
     *
     * @return \Illuminate\Http\Response
     */
    public function response()
    {
        return response()->noContent();
    }
}
