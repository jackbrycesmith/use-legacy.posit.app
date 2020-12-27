<?php

namespace App\Models\States\Posit;

use Spatie\ModelStates\Exceptions\CouldNotPerformTransition;
use Spatie\ModelStates\Exceptions\TransitionNotFound;
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
            ->allowTransition(Published::class, Accepted::class, TransitionToAccepted::class)
            ->allowTransition(Published::class, Expired::class)
            ->allowTransition(Published::class, Amending::class);
    }

    public static function canBypassPublicAuthAccess(): bool
    {
        return in_array(self::getMorphClass(), self::statesThatCanBypassPublicAuthAccess());
    }

    public static function statesThatCanBypassPublicAuthAccess(): array
    {
        return [
            Draft::getMorphClass(), Expired::getMorphClass()
        ];
    }

    public static function hasBeenInPublishedState(): bool
    {
        return in_array(self::getMorphClass(), self::statesThatHaveBeenPublished());
    }

    public static function statesThatHaveBeenPublished(): array
    {
        return [
            Published::getMorphClass(),
            Accepted::getMorphClass(),
            Amending::getMorphClass(),
            Expired::getMorphClass()
        ];
    }

    public static function canUpdateThePosit(): bool
    {
        return in_array(self::getMorphClass(), self::statesThatCanUpdateThePosit());
    }

    public static function statesThatCanUpdateThePosit(): array
    {
        return [
            Draft::getMorphClass(),
            Amending::getMorphClass(),
        ];
    }
}
