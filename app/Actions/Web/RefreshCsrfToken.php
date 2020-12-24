<?php

namespace App\Actions\Web;

use Illuminate\Routing\Router;
use Lorisleiva\Actions\Action;

class RefreshCsrfToken extends Action
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
        $router->middleware(['web'])->post('/api/refresh-csrf-token', static::class);
    }

    /**
     * Execute the action and return a result.
     *
     * @return mixed
     */
    public function handle()
    {
        // Route added in VerifyCsrfToken $except...
        // so as we're using the 'web' middleware group...
        // this will set a cookie with a new XSRF-TOKEN...
    }
}
