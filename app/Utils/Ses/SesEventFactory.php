<?php

namespace App\Utils\Ses;

use App\Utils\Ses\Events\Complaint;
use App\Utils\Ses\Events\Other;
use App\Utils\Ses\Events\PermanentBounce;
use App\Utils\Ses\Events\SesEvent;

class SesEventFactory
{
    protected static array $sesEvents = [
        Complaint::class,
        PermanentBounce::class,
    ];

    public static function createForPayload(array $payload): SesEvent
    {
        $sesEvent = collect(static::$sesEvents)
            ->map(fn (string $sesEventClass) => new $sesEventClass($payload))
            ->first(fn (SesEvent $sesEvent) => $sesEvent->canHandlePayload());

        return $sesEvent ?? new Other($payload);
    }
}
