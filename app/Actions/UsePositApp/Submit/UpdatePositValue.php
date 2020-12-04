<?php

namespace App\Actions\UsePositApp\Submit;

use App\Models\Posit;
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
            ->put('/posit/{posit:uuid}/upsert-value', static::class)
            ->where('posit', Constant::PATTERN_UUID)
            ->name('use.submit.upsert-posit-value');
    }

    /**
     * Determine if the user is authorized to make this action.
     *
     * @param \App\Models\Posit $posit The posit
     *
     * @return bool
     */
    public function authorize(Posit $posit)
    {
        return $this->can('update', $posit);
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
                Rule::in(Posit::ALLOWED_VALUE_CURRENCIES)
            ]
        ];
    }

    /**
     * Execute the action and return a result.
     *
     * @return mixed
     */
    public function handle(Posit $posit)
    {
        $posit->update($this->validated());
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
