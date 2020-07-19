<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use CloudCreativity\LaravelStripe\Facades\Stripe;
use CloudCreativity\LaravelStripe\LaravelStripe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        LaravelStripe::withoutMigrations();

        // Stripe::authorizeUrl();

        LaravelStripe::currentOwnerResolver(function (Request $request) {
            // TODO return the CORRECT organisation; do I need to store something in the db/cache or something to know which organisation...??
            // think i need to write a new StateProviderInterface that I can pass in the org_id/uuid IN ADDITION to the csrf token of the user or something
            // then register it above here to overide on LaravelStripe
            // too tired right now...
            return Auth::user()->organisations->first();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);


        // \URL::forceScheme('https');
        // if(config('app.env') === 'production') {
        // }
    }
}
