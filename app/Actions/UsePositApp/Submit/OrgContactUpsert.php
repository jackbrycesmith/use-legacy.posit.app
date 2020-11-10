<?php

namespace App\Actions\UsePositApp\Submit;

use App\Models\Organisation;
use App\Models\Team;
use App\Models\TeamContact;
use App\Utils\Constant;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Action;

class OrgContactUpsert extends Action
{
    public static function routes(Router $router)
    {
        $router->domain(use_posit_domain())
            ->middleware(['web', 'auth'])
            ->post('/team/{team:uuid}/contacts/add', static::class)
            ->where('team', Constant::PATTERN_UUID)
            ->name('use.team.contacts.add-submit');

        $router->domain(use_posit_domain())
            ->middleware(['web', 'auth'])
            ->put('/team/{team:uuid}/contacts/{contact}', static::class)
            ->where('team', Constant::PATTERN_UUID)
            ->name('use.team.contacts.update');
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
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'max:255'],
        ];
    }

    /**
     * Execute the action and return a result.
     *
     * @return mixed
     */
    public function handle(Team $team, ?TeamContact $contact = null)
    {
        // TODO; tests + less naive e.g. saving directly on meta?
        if (is_null($contact)) {
            $team->contacts()->create([
                'meta' => $this->validated()
            ]);
        } else {
            $contact->update(['meta' => $this->validated()]);
        }

        return Redirect::route('use.team.contacts', $team);
    }
}
