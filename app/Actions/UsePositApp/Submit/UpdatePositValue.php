<?php

namespace App\Actions\UsePositApp\Submit;

use App\Models\Proposal;
use App\Utils\Constant;
use Illuminate\Routing\Router;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\Action;

class UpdatePositValue extends Action
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
            ->put('/proposal/{proposal:uuid}/upsert-value', static::class)
            ->where('proposal', Constant::PATTERN_UUID)
            ->name('use.submit.upsert-posit-value');
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
        $maxValue = (float) ("1" . str_repeat("0", config('posit-settings.proposal.value_max_digits'))) - 0.01;

        return [
            'value_amount' => [
                'bail',
                'nullable',
                'numeric',
                'gte:0',
                'regex:/^\d+(\.\d{1,2})?$/', // Max 2 decimal places
                "max:$maxValue"
            ],
            'value_currency_code' => [
                'bail',
                'required',
                Rule::in(Proposal::ALLOWED_VALUE_CURRENCIES)
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
