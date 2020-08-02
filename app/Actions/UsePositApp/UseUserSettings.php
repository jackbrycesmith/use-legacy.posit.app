<?php

namespace App\Actions\UsePositApp;

use Illuminate\Routing\Router;
use Inertia\Inertia;
use Lorisleiva\Actions\Action;

class UseUserSettings extends Action
{
    public static function routes(Router $router)
    {
        $router->domain(use_posit_domain())->middleware(['web', 'auth'])->get('/user/settings', static::class)->name('use.user.settings');
    }

    /**
     * Execute the action and return a result.
     *
     * @return mixed
     */
    public function handle()
    {
        return Inertia::render('Use/UserSettings', [
            //
        ]);
    }
}
