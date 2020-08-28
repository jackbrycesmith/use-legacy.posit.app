<?php

namespace App\Actions\UsePositApp\Submit;

use App\Http\Resources\OrgContactResource;
use App\Models\OrganisationContact;
use App\Models\Proposal;
use App\Utils\Constant;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Request;
use Lorisleiva\Actions\Action;

class ProposalRecipientUpsert extends Action
{
    public static function routes(Router $router)
    {
        $router->domain(use_posit_domain())
            ->middleware(['web', 'auth'])
            ->post('/proposal/{proposal:uuid}/recipients/add', static::class)
            ->where('proposal', Constant::PATTERN_UUID)
            ->name('use.proposal.recipients.add-submit');

        $router->domain(use_posit_domain())
            ->middleware(['web', 'auth'])
            ->put('/proposal/{proposal:uuid}/recipients/{recipient}', static::class)
            ->where('proposal', Constant::PATTERN_UUID)
            ->name('use.proposal.recipients.update');
    }

    /**
     * Determine if the user is authorized to make this action.
     *
     * @return bool
     */
    public function authorize(Proposal $proposal, ?OrganisationContact $recipient = null)
    {
        return $this->can('upsertRecipient', [$proposal, $recipient]);
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    public function rules()
    {
        if (Request::routeIs('use.proposal.recipients.add-submit')) {
            return [
                'name' => ['required', 'max:255'],
            ];
        }

        return [];
    }

    /**
     * Execute the action and return a result.
     *
     * @param \App\Models\Proposal $proposal The proposal
     * @param \App\Models\OrganisationContact|nullable $recipient The recipient
     *
     * @return mixed
     */
    public function handle(Proposal $proposal, ?OrganisationContact $recipient = null)
    {
        if (is_null($recipient)) {
            $org = $proposal->organisation;
            $recipient = $org->contacts()->create([
                'meta' => $this->validated()
            ]);
        }

        $proposal->recipients()->sync([$recipient->id]);

        return $recipient;
    }

    public function response(OrganisationContact $recipient)
    {
        return new OrgContactResource($recipient);
    }
}
