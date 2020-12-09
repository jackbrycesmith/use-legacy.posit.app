<?php

namespace App\Models\States\Posit;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class PositState extends State
{
    /**
     * Define the allowed state transitions.
     *
     * @return StateConfig The state configuration.
     */
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Draft::class)
            ->allowTransition([Draft::class, Amending::class], Published::class, TransitionToPublished::class)
            ->allowTransition(Published::class, Accepted::class)
            ->allowTransition(Published::class, Expired::class)
            ->allowTransition(Published::class, Amending::class);
    }
}
