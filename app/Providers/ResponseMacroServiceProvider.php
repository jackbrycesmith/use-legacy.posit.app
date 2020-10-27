<?php

namespace App\Providers;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('inertiable', function ($inertiaComponent, array $props = []) {
            if (request()->inertia()) {
                return Inertia::render($inertiaComponent, $props);
            }

            if (request()->expectsJson()) {
                array_walk_recursive($props, function (&$prop) {
                    if ($prop instanceof Closure) {
                        $prop = App::call($prop);
                    }

                    if ($prop instanceof Responsable) {
                        $prop = $prop->toResponse(request())->getData();
                    }

                    if ($prop instanceof Arrayable) {
                        $prop = $prop->toArray();
                    }
                });

                return new JsonResponse($props);
            }

            return Inertia::render($inertiaComponent, $props);
        });
    }
}
