<?php

namespace App\Utils\Ses\Events;

use Carbon\Carbon;
use DateTimeInterface;

abstract class SesEvent
{
    protected array $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    abstract public function canHandlePayload(): bool;

    abstract public function handle();

    public function getTimestamp(): ?DateTimeInterface
    {
        $eventType = strtolower($this->payload['eventType']);

        $timestamp = $this->payload[$eventType]['timestamp'];

        return $timestamp ? Carbon::parse($timestamp)->setTimezone(config('app.timezone')) : null;
    }
}
