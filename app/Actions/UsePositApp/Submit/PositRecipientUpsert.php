<?php

namespace App\Actions\UsePositApp\Submit;

use App\Http\Resources\TeamContactResource;
use App\Models\Posit;
use App\Models\TeamContact;
use App\Utils\Constant;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Request;
use Lorisleiva\Actions\Action;

class PositRecipientUpsert extends Action
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
            ->post('/posit/{posit:uuid}/recipients/add', static::class)
            ->where('posit', Constant::PATTERN_UUID)
            ->name('use.posit.recipients.add-submit');

        $router->domain(use_posit_domain())
            ->middleware(['web', 'auth:sanctum', 'verified'])
            ->put('/posit/{posit:uuid}/recipients/{recipient}', static::class)
            ->where('posit', Constant::PATTERN_UUID)
            ->name('use.posit.recipients.update');
    }

    /**
     * Determine if the user is authorized to make this action.
     *
     * @return bool
     */
    public function authorize(Posit $posit, ?TeamContact $recipient = null)
    {
        return $this->can('upsertRecipient', [$posit, $recipient]);
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    public function rules()
    {
        if (Request::routeIs('use.posit.recipients.add-submit')) {
            return [
                'name' => ['required', 'max:255'],
            ];
        }

        return [];
    }

    /**
     * Execute the action and return a result.
     *
     * @param \App\Models\Posit $posit The posit
     * @param \App\Models\TeamContact|nullable $recipient The recipient
     *
     * @return mixed
     */
    public function handle(Posit $posit, ?TeamContact $recipient = null)
    {
        if (is_null($recipient)) {
            $team = $posit->team;
            $recipient = $team->contacts()->create([
                'meta' => $this->validated()
            ]);
        }

        $posit->recipients()->sync([$recipient->id]);

        return $recipient;
    }

    public function response(TeamContact $recipient)
    {
        return new TeamContactResource($recipient);
    }
}
