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
        return []; // TODO
    }

    /**
     * Execute the action and return a result.
     *
     * @return mixed
     */
    public function handle(Request $request, Posit $posit)
    {
        // TODO like redis locking & stuff

        // TODO this is extremely naive/not production ready
        $posit->positContents()->updateOrCreate(
            ['posit_id' => $posit->id],
            ['content' => $request->all()]
        );

    }
}
