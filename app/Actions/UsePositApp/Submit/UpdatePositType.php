<?php

namespace App\Actions\UsePositApp\Submit;

use App\Enums\PositType;
use App\Models\Posit;
use App\Utils\Constant;
use Illuminate\Routing\Router;
use Lorisleiva\Actions\Action;
use Spatie\Enum\Laravel\Rules\EnumRule;

class UpdatePositType extends Action
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
            ->put('/posit/{posit:uuid}/update-type', static::class)
            ->where('posit', Constant::PATTERN_UUID)
            ->name('use.submit.update-posit-type');
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
        return [
            'type' => [
                'required',
                new EnumRule(PositType::class)
            ],
        ];
    }

    /**
     * Execute the action and return a result.
     *
     * @param \App\Models\Posit $posit The posit
     *
     * @return mixed
     */
    public function handle(Posit $posit)
    {
        $posit->update($this->validated());

        // TODO broadcast this change to other posit viewers...
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
