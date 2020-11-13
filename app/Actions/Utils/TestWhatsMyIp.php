<?php

namespace App\Actions\Utils;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Lorisleiva\Actions\Action;

class TestWhatsMyIp extends Action
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
        $router->get('/test/whats-my-ip', static::class);
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
    public function handle(Request $request)
    {
        dump($request->ip());
    }

    /**
     * The action http response.
     *
     * @return \Illuminate\Http\Response
     */
    public function response()
    {
        // Return a http response from @handle.
    }
}
