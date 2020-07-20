<?php

namespace App\Actions\Integrations\Stripe;

use CloudCreativity\LaravelStripe\Config;
use CloudCreativity\LaravelStripe\Contracts\Connect\StateProviderInterface;
use CloudCreativity\LaravelStripe\Events\OAuthError;
use CloudCreativity\LaravelStripe\Events\OAuthSuccess;
use CloudCreativity\LaravelStripe\Http\Requests\AuthorizeConnect;
use CloudCreativity\LaravelStripe\Log\Logger;
use Illuminate\Http\Response;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Action;

class HandleStripeConnectOauthCallback extends Action
{
    public static function routes(Router $router)
    {
        // TODO
        $router->middleware(['web', 'auth'])->get('/stripe/connect/authorize', static::class);
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
        return [];
    }

    /**
     * Execute the action and return a result.
     *
     * @return mixed
     */
    public function handle(AuthorizeConnect $request, StateProviderInterface $state, Logger $log)
    {
        $data = collect($request->query())->only([
            'code',
            'scope',
            'error',
            'error_description',
        ]);

        $owner = $request->owner();
        // TODO check user has access to the organisation...
        // TODO check whether stripe account is connected?

        $log->log('Received OAuth redirect.', $data->all());

        /** Check the state parameter and return an error if it is not as expected. */
        if (true !== $state->check($request->stripeConnectOauthStateSessionToken())) {
            return $this->error(Response::HTTP_FORBIDDEN, [
                'error' => OAuthError::LARAVEL_STRIPE_FORBIDDEN,
                'error_description' => 'Invalid authorization token.',
            ], $owner);
        }

        /** If Stripe has told there is an error, return an error response. */
        if ($data->has('error')) {
            return $this->error(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $data,
                $owner
            );
        }

        /** Otherwise return our success view. */
        return $this->success($data, $owner);
    }

    /**
     * Handle success.
     *
     * @param $data
     * @param $user
     * @return Response
     */
    protected function success($data, $org)
    {
        event($success = new OAuthSuccess(
            $data['code'],
            $data['scope'],
            $org,
            Config::connectSuccessView()
        ));

        return Redirect::route('use.org.view', $org);

        // return response()->view($success->view, $success->all());
    }

    /**
     * Handle an error.
     *
     * @param int $status
     * @param $data
     * @param $org
     * @return Response
     */
    protected function error($status, $data, $org)
    {
        event($error = new OAuthError(
            $data['error'],
            $data['error_description'],
            $org,
            Config::connectErrorView()
        ));

        return Redirect::route('use.index');

        // return response()->view(
        //     $error->view,
        //     $error->all(),
        //     $status
        // );
    }
}
