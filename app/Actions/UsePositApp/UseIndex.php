<?php

namespace App\Actions\UsePositApp;

use App\Http\Resources\ProposalResource;
use Illuminate\Routing\Router;
use Inertia\Inertia;
use Lorisleiva\Actions\Action;

class UseIndex extends Action
{
    public static function routes(Router $router)
    {
        $router->domain(use_posit_domain())->middleware(['web'])->get('/', static::class)->name('use.index');
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
        $proposals = optional($this->user(), function ($user) {
            return ProposalResource::collection($user->proposals);
        });

        return Inertia::render('Use/Index', [
            'proposals' => $proposals ?? []
        ]);
    }
}
