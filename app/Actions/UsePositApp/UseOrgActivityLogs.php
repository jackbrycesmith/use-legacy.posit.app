<?php

namespace App\Actions\UsePositApp;

use App\Http\Resources\OrganisationResource;
use App\Models\Organisation;
use Illuminate\Routing\Router;
use Inertia\Inertia;
use Lorisleiva\Actions\Action;

class UseOrgActivityLogs extends Action
{
    public static function routes(Router $router)
    {
        $router->domain(use_posit_domain())->middleware(['web', 'auth'])->get('/org/{org:uuid}/activity-logs', static::class)->name('use.org.activity-logs');
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
        // $org->loadMissing(['']); TODO load organsiation activity logs (e.g. across all proposals)

        return Inertia::render('Use/OrgActivityLogs', [
            'org' => new OrganisationResource($org)
        ]);
    }
}
