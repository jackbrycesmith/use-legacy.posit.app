<?php

namespace App\Actions\UsePositApp;

use App\Http\Resources\OrgContactResource;
use App\Http\Resources\OrganisationResource;
use App\Models\Organisation;
use App\Models\TeamContact;
use App\Utils\Constant;
use Illuminate\Routing\Router;
use Inertia\Inertia;
use Lorisleiva\Actions\Action;

class UseOrgContactsUpsert extends Action
{
    public static function routes(Router $router)
    {
        $router->domain(use_posit_domain())
            ->middleware(['web', 'auth'])
            ->get('/org/{org:uuid}/contacts/add', static::class)
            ->where('org', Constant::PATTERN_UUID)
            ->name('use.org.contacts.add');

        $router->domain(use_posit_domain())
            ->middleware(['web', 'auth'])
            ->get('/org/{org:uuid}/contacts/{contact}/edit', static::class)
            ->where('org', Constant::PATTERN_UUID)
            ->name('use.org.contacts.edit');
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
     * Execute the action and return a result.
     *
     * @return mixed
     */
    public function handle(Organisation $org, ?TeamContact $contact = null)
    {
        return Inertia::render('Use/OrgContactsUpsert', [
            'org' => new OrganisationResource($org),
            'contact' => is_null($contact) ? null : new OrgContactResource($contact),
        ]);
    }
}
