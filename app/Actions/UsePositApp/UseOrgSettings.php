<?php

namespace App\Actions\UsePositApp;

use App\Http\Resources\OrganisationResource;
use App\Models\Organisation;
use Illuminate\Routing\Router;
use Inertia\Inertia;
use Lorisleiva\Actions\Action;

class UseOrgSettings extends Action
{
    public static function routes(Router $router)
    {
        $router->domain(use_posit_domain())->middleware(['web', 'auth'])->get('/org/{org:uuid}/settings', static::class)->name('use.org.settings');
    }

    /**
     * Determine if the user is authorized to make this action.
     *
     * @return bool
     */
    public function authorize(Organisation $org)
    {
        return $this->can('view', $org);
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
    public function handle(Organisation $org)
    {
        $org->loadMissing(['users', 'stripeAccount']);

        return Inertia::render('Use/OrgSettings', [
            'org' => new OrganisationResource($org)
        ]);
    }
}
