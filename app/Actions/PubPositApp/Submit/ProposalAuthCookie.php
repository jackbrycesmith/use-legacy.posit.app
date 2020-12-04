<?php

namespace App\Actions\PubPositApp\Submit;

use App\Http\PublicPositAccessCookie;
use App\Models\Posit;
use App\Utils\Constant;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\Action;

class ProposalAuthCookie extends Action
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
        $router->domain(pub_posit_domain())
            ->middleware(['web'])
            ->post('/posit/{posit:uuid}/auth', static::class)
            ->where('posit', Constant::PATTERN_UUID)
            ->name('pub.posit.submit.posit-auth-cookie');
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
    public function handle(Request $request, Posit $posit)
    {
        $recipient = $posit->recipientForAccessCode($request->access_code);

        if (is_null($recipient)) {
            $this->sendFailedProposalAccessCodeResponse();
        }

        return redirect(route('pub.posit.view', $posit))->withCookie(
            PublicPositAccessCookie::create($recipient)
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
