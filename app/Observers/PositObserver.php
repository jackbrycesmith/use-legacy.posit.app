<?php

namespace App\Observers;

use App\Models\Posit;

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

        if (is_null($posit->theme)) {
            $posit->theme = $posit->defaultTheme();
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
