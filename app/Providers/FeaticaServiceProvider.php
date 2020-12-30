<?php

namespace App\Providers;

use Featica\Featica;
use Featica\FeaticaApplicationServiceProvider;
use Featica\Feature;
use Illuminate\Support\Facades\Gate;

class FeaticaServiceProvider extends FeaticaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

    }

    /**
     * Configure the features flags available within the system.
     *
     * @return void
     */
    protected function configureFeatures()
    {
        Featica::add(new Feature(
            key: 'coinbase-commerce',
            type: Featica::TYPE_TEAM,
            state: Featica::STATE_OFF,
        ));
    }

    /**
     * Register the Featica dashboard gate.
     *
     * This gate determines who can access Featica dashboard in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewFeatica', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }
}
