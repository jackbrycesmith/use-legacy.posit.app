<?php

namespace App\Actions\UsePositApp\Submit;

use App\Models\Organisation;
use App\Models\OrganisationContact;
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
            ->post('/org/{org:uuid}/contacts/add', static::class)
            ->where('org', Constant::PATTERN_UUID)
            ->name('use.org.contacts.add-submit');

        $router->domain(use_posit_domain())
            ->middleware(['web', 'auth'])
            ->put('/org/{org:uuid}/contacts/{contact}', static::class)
            ->where('org', Constant::PATTERN_UUID)
            ->name('use.org.contacts.update');
    }

    /**
     * Determine if the user is authorized to make this action.
     *
     * @return bool
     */
    public function authorize(Organisation $org, ?OrganisationContact $contact = null)
    {
        return $this->can('upsertContact', [$org, $contact]);
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
    public function handle(Organisation $org, ?OrganisationContact $contact = null)
    {
        // TODO; tests + less naive e.g. saving directly on meta?
        if (is_null($contact)) {
            $org->contacts()->create([
                'meta' => $this->validated()
            ]);
        } else {
            $contact->update(['meta' => $this->validated()]);
        }

        return Redirect::route('use.org.contacts', $org);
    }
}
