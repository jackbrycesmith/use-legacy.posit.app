<?php

namespace App\Observers;

use App\Enums\PositType;
use App\Models\Posit;
use App\Models\Values\PositConfig;
use Illuminate\Support\Arr;

class PositObserver
{
    /**
     * Handle the posit "creating" event.
     *
     * @param  \App\Models\Posit  $posit
     * @return void
     */
    public function creating(Posit $posit)
    {
        if (is_null($posit->name)) {
            $posit->name = $posit->defaultName();
        }

        // Accessing ->type directly will crash because non null check
        if (is_null(Arr::get($posit->getAttributes(), 'type'))) {
            $posit->type = PositType::accept_only();
        }

        if (is_null($posit->config)) {
            $posit->config = PositConfig::defaults();
        }

        if (is_null($posit->value_currency_code)) {
            $posit->value_currency_code = $posit->defaultValueCurrency();
        }
    }

    /**
     * Handle the posit "created" event.
     *
     * @param \App\Models\Posit $posit
     * @return void
     */
    public function created(Posit $posit)
    {
        //
    }

    /**
     * Handle the posit "updated" event.
     *
     * @param  \App\Models\Posit  $posit
     * @return void
     */
    public function updated(Posit $posit)
    {
        //
    }

    /**
     * Handle the posit "deleted" event.
     *
     * @param  \App\Models\Posit  $posit
     * @return void
     */
    public function deleted(Posit $posit)
    {
        //
    }

    /**
     * Handle the posit "restored" event.
     *
     * @param  \App\Models\Posit  $posit
     * @return void
     */
    public function restored(Posit $posit)
    {
        //
    }

    /**
     * Handle the posit "force deleted" event.
     *
     * @param  \App\Models\Posit  $posit
     * @return void
     */
    public function forceDeleted(Posit $posit)
    {
        //
    }
}
