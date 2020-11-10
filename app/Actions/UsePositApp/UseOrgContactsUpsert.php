<?php

namespace App\Actions\UsePositApp;

use App\Http\Resources\TeamContactResource;
use App\Http\Resources\TeamResource;
use App\Models\Team;
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
            ->middleware(['web', 'auth:sanctum', 'verified'])
            ->get('/team/{team:uuid}/contacts/add', static::class)
            ->where('team', Constant::PATTERN_UUID)
            ->name('use.team.contacts.add');

        $router->domain(use_posit_domain())
            ->middleware(['web', 'auth:sanctum', 'verified'])
            ->get('/team/{team:uuid}/contacts/{contact}/edit', static::class)
            ->where('team', Constant::PATTERN_UUID)
            ->name('use.team.contacts.edit');
    }

    /**
     * Determine if the user is authorized to make this action.
     *
     * @return bool
     */
    public function authorize(Team $team, ?TeamContact $contact = null)
    {
        return $this->can('upsertContact', [$team, $contact]);
    }

    /**
     * Execute the action and return a result.
     *
     * @return mixed
     */
    public function handle(Team $team, ?TeamContact $contact = null)
    {
        return Inertia::render('Use/OrgContactsUpsert', [
            'org' => new TeamResource($team),
            'contact' => is_null($contact) ? null : new TeamContactResource($contact),
        ]);
    }
}
