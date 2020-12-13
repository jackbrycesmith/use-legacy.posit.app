<?php

namespace App\Models\States\PositPayment;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class PositPaymentState extends State
{
    /**
     * Define the allowed state transitions.
     *
     * @return StateConfig The state configuration.
     */
    public static function config(): StateConfig
    {
        return parent::config()
            // Transition to 'pending'
            ->default(Pending::class)
            ->allowTransition(Processing::class, Pending::class)

            // Transition to 'processing'
            ->allowTransition(Pending::class, Processing::class)
            ->allowTransition(PartiallyPaid::class, Processing::class)

            // Transition to 'paid'
            ->allowTransition(Pending::class, Paid::class)
            ->allowTransition(Processing::class, Paid::class)
            ->allowTransition(PartiallyPaid::class, Paid::class)

            // Transition to 'partially_paid'
            ->allowTransition(Pending::class, PartiallyPaid::class)
            ->allowTransition(Processing::class, PartiallyPaid::class)
            ->allowTransition(PartiallyPaid::class, PartiallyPaid::class)

            // Transition to 'partially_refunded'
            ->allowTransition(Paid::class, PartiallyRefunded::class)
            ->allowTransition(PartiallyPaid::class, PartiallyRefunded::class)
            ->allowTransition(PartiallyRefunded::class, PartiallyRefunded::class)

            // Transition to 'refunded'
            ->allowTransition(Paid::class, Refunded::class)
            ->allowTransition(PartiallyPaid::class, Refunded::class)
            ->allowTransition(PartiallyRefunded::class, Refunded::class)

            // Transition to 'cancelled'
            ->allowTransition(Pending::class, Cancelled::class);
    }
}
