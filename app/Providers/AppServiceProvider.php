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
        $this->registerLaravelStripeConnect();
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

    /**
     * Setup jackbrycesmith/laravel-connect-stripe
     *
     * @return self
     */
    protected function registerLaravelStripeConnect()
    {
        LaravelStripe::withoutMigrations();

        LaravelStripe::connectState(\App\Utils\StripeOauthSessionState::class);

        LaravelStripe::currentOwnerResolver(function (Request $request) {
            return $request->stripeConnectOauthOrg();
        });

        return $this;
    }
}
