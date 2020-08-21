<?php

namespace App\Actions\PubPositApp\Submit;

use App\Http\PublicProposalAccessCookie;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\Action;

class ProposalAuthCookie extends Action
{
    public static function routes(Router $router)
    {
        $router->domain(pub_posit_domain())
            ->post('/proposal/{proposal:uuid}/auth', static::class)
            ->middleware(['web'])
            ->name('pub.proposal.submit.proposal-auth-cookie');
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
        return [
            'access_code' => ['required', 'string', 'max:75']
        ];
    }

    /**
     * Execute the action and return a result.
     *
     * @return mixed
     */
    public function handle(Request $request, Proposal $proposal)
    {
        $recipient = $proposal->recipientForAccessCode($request->access_code);

        if (is_null($recipient)) {
            $this->sendFailedProposalAccessCodeResponse();
        }

        return redirect(route('pub.proposal.view', $proposal))->withCookie(
            PublicProposalAccessCookie::create($recipient)
        );
    }

    /**
     * Sends a failed proposal access code response.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedProposalAccessCodeResponse()
    {
        throw ValidationException::withMessages([
            'access_code' => [trans('auth.failed')],
        ]);
    }
}
