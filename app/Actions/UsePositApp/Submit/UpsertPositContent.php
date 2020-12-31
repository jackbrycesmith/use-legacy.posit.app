<?php

namespace App\Actions\UsePositApp\Submit;

use App\Models\Posit;
use App\Utils\Constant;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Lorisleiva\Actions\Action;

class UpsertPositContent extends Action
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
            ->put('/posit/{posit:uuid}/upsert-content', static::class)
            ->where('posit', Constant::PATTERN_UUID)
            ->name('use.submit.upsert-posit-content');
    }

    /**
     * Determine if the user is authorized to make this action.
     *
     * @param Posit $posit
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
                'nullable',
            ],
            'content' => [
                'nullable',
            ]
        ];
    }

    /**
     * Execute the action and return a result.
     *
     * @param Posit $posit
     *
     * @return mixed
     */
    public function handle(Posit $posit)
    {
        // TODO like redis locking & stuff

        // TODO this is extremely naive/not production ready
        $posit->content = $this->validated();
        $posit->save();
    }
}
